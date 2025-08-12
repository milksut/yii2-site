<?php

namespace portalium\site\controllers\web;

use Yii;
use portalium\site\Module;
use yii\helpers\ArrayHelper;
use portalium\site\models\Setting;
use portalium\site\models\SettingValue;
use portalium\web\Controller as WebController;

class SettingController extends WebController
{
    public function actionIndex()
    {
        if(!Yii::$app->user->can('siteWebSettingIndex')){
            throw new \yii\web\ForbiddenHttpException(Module::t('You are not allowed to access this page.'));
        }
        $settings = Setting::find()
            ->orderBy(['module' => SORT_ASC,'id' => SORT_ASC,'name'=>SORT_ASC])
            ->indexBy('id')
            ->all();
        $settingsGroup = ArrayHelper::index($settings, null, 'module');
        
        foreach ($settings as $setting) {
            $setting->value = ($this->isJson($setting->value) && in_array($setting->type, SettingValue::getScenarios()['multiple'])) ? json_decode($setting->value) : $setting->value;
        }

        return $this->render('index', [
            'settings' => $settings,
            'settingsGroup' => $settingsGroup
        ]);
    }

    public function actionUpdate()
    {
        if(!Yii::$app->user->can('siteWebSettingUpdate')){
            throw new \yii\web\ForbiddenHttpException(Module::t('You are not allowed to access this page.'));
        }
        $settings = Setting::find()->indexBy('id')->all();
        $settingsData = Yii::$app->request->post('Setting');

        foreach ($settings as $setting) {
            $valueModel = new SettingValue();
            if(!isset($settingsData["$setting->module-$setting->id"])){
                continue;
            }else{
                
            }

            $valueModel->value = $settingsData["$setting->module-$setting->id"]['value'];
            
            $valueModel->scenario = $setting->type;
            if ($valueModel->validate()) {
                $settingsData["$setting->module-$setting->id"]['value'] = (is_array($valueModel->value)) ? json_encode($valueModel->value) : $valueModel->value;
            }else{
                Yii::$app->session->addFlash('error', Module::t('There are an error. Settings not saved.'));
                return $this->redirect('index');
            }

            $setting->value = $settingsData["$setting->module-$setting->id"]['value'];

            if ($setting->validate()) {
                $setting->save();
                if ($setting->name === 'theme::page_size') {
                    Yii::$app->session->set('theme::page_size', (int)$setting->value);
                }
            }else{
                Yii::$app->session->addFlash('error', Module::t('There are an error. Settings not saved.'));
                return $this->redirect('index');
            }
        }
        
        Yii::$app->session->addFlash('success', Module::t('Settings saved'));

        return $this->redirect('index');
    }

    public function isJson($string) {
        if(!$string) {
            return false;
        }
        return json_last_error() === JSON_ERROR_NONE;
    }
}
