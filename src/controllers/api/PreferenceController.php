<?php

namespace portalium\site\controllers\api;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use portalium\site\models\Preference;
use portalium\site\models\Setting;
use portalium\site\models\SettingValue;

class PreferenceController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator']['formats']['application/json']
            = Response::FORMAT_JSON;

        return $behaviors;
    }

    /**
     * GET /api/site/preference
     */
    public function actionIndex()
    {
        $preferences = Preference::find()
            ->where([
                'id_user' => Yii::$app->user->id,
            ])
            ->with('setting')
            ->all();

        $result = [];

        foreach ($preferences as $preference) {
            $result[] = [
                'id_preference' => $preference->id_preference,
                'id_setting' => $preference->id_setting,
                'id_workspace' => $preference->id_workspace?? null,
                'setting_name' => $preference->setting->name ?? null,
                'value' => $preference->value,
            ];
        }

        return $result;
    }

    /**
     * GET /api/site/preference/{id}
     */
    public function actionView($id)
    {
        $model = Preference::findOne([
            'id_preference' => $id,
            'id_user' => Yii::$app->user->id,
        ]);

        if (!$model) {
            throw new NotFoundHttpException('Preference not found.');
        }

        return $model;
    }

    /**
     * POST /api/site/preference
     * body: { setting_name | id_setting, id_workspace?, value }
     */
    public function actionCreate()
    {
        $body = Yii::$app->request->bodyParams;
 
        $setting = $this->resolveSetting($body);
 
        $value = isset($body['value']) ? (string) $body['value'] : null;

        if ($value === null ) 
        {
            Yii::$app->response->statusCode = 422;
            return ['value' => ['Invalid value.']];
        }
 
        $idWorkspace = $body['id_workspace'] ?? null;
        if ($idWorkspace === '') 
        {
            $idWorkspace = null;
        }
 
        // Upsert: aynı kullanıcı + ayar + workspace için tek kayıt.
        $model = Preference::findOne([
            'id_user'      => Yii::$app->user->id,
            'id_setting'   => $setting->id,
            'id_workspace' => $idWorkspace,
        ]);
 
        $isNew = $model === null;
        if ($isNew) {
            $model = new Preference();
            $model->id_user      = Yii::$app->user->id;
            $model->id_workspace = $idWorkspace;
            $model->id_setting   = $setting->id;
        }
        $model->value = $value;
 
        if ($model->save()) {
            Yii::$app->response->statusCode = $isNew ? 201 : 200;
            return $model;
        }
 
        Yii::$app->response->statusCode = 422;
        return $model->errors;
    }

     /**
     * PUT /api/site/preference/{id}
     * Yalnızca value değişir.
     */
    public function actionUpdate($id)
    {
        $model = Preference::findOne([
            'id_preference' => $id,
            'id_user'       => Yii::$app->user->id,
        ]);
 
        if (!$model) {
            throw new NotFoundHttpException('Preference not found.');
        }
 
        $body = Yii::$app->request->bodyParams;
        if (!array_key_exists('value', $body)) {
            throw new BadRequestHttpException('value is required.');
        }
 
        $model->value = (string) $body['value'];
 
        if ($model->save()) {
            return $model;
        }
 
        Yii::$app->response->statusCode = 422;
        return $model->errors;
    }
 
    private function resolveSetting(array $body): Setting
    {
        if (!empty($body['setting_name'])) {
            $setting = Setting::findOne(['name' => $body['setting_name']]);
        } elseif (!empty($body['id_setting'])) {
            $setting = Setting::findOne((int) $body['id_setting']);
        } else {
            throw new BadRequestHttpException('setting_name or id_setting is required.');
        }
 
        if ($setting === null) {
            throw new NotFoundHttpException('Setting not found.');
        }
 
        return $setting;
    }

    /**
     * DELETE /api/site/preference/{id}
     */
    public function actionDelete($id)
    {
        $model = Preference::findOne([
            'id_preference' => $id,
            'id_user' => Yii::$app->user->id,
        ]);

        if (!$model) {
            throw new NotFoundHttpException('Preference not found.');
        }

        $model->delete();

        return [
            'success' => true
        ];
    }
}