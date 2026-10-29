<?php

namespace portalium\site\components;

use portalium\site\models\Preference;
use yii\base\Component;
use portalium\site\Module;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\base\InvalidConfigException;
use portalium\site\models\Setting as Settings;

class Setting extends Component
{
    public function getConfig($name)
    {
        return self::decode(self::findSetting($name)->config);
    }

    public function setConfig($name, $value)
    {
        $setting = self::findSetting($name);
        $setting->config = $value;
        return $setting->save();
    }

    public function getAll()
    {
        return ArrayHelper::map(Settings::find()->asArray()->all(),'name','value');
    }

    public function getValue($name)
    {
        $settingModel=self::findSetting($name);

        if(isset(\Yii::$app->user->id))
        {
            $preferenceModel=self::findPreference($settingModel->id);

            if($preferenceModel)
            {
                return self::decode($preferenceModel->value);
            }
            else
            {
                return self::decode($settingModel->value);
            }

        }
        return self::decode($settingModel->value);

    }

    public static function getSetting($name)
    {
        $settingModel=self::findSetting($name);
        $preferenceModel=self::findPreference($settingModel->id);

        if($preferenceModel)
        {
            return $preferenceModel;
        }
        else
        {
            return $settingModel;
        }
    }

    private function decode($value)
    {
        return ($this->isJson($value,true)) ? json_decode($value, true): $value;
    }

    private static function findSetting($name)
    {
        if (($setting = Settings::findOne(['name' => $name])) !== null) {
            return $setting;
        }

        throw new NotFoundHttpException(Module::t('The requested '.$name.' setting does not exist.'));
    }

    private static function findPreference($id)
    {
        $id_user = \Yii::$app->user->id;
        $id_workspace = \Yii::$app->workspace->id;

        $preference = Preference::find()
            ->where([
                'id_setting' => $id,
                'id_user' => $id_user,
            ])
            ->andWhere([
                'or',
                ['id_workspace' => $id_workspace],
                ['id_workspace' => null],
            ])
            ->orderBy(['id_workspace' => SORT_DESC]) //It will prefer workspace pereferance
            ->one();

        return $preference ? $preference : false;
    }

    function isJson($value) {
        $value = strval($value);
        json_decode($value);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
