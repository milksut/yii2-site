<?php

namespace portalium\site\controllers\api;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
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
     * GET /api/preferences
     */
    public function actionIndex()
    {
        $preferences = Preference::find()
            ->where([
                'id_user' => Yii::$app->user->id,
                'id_workspace' => Yii::$app->workspace->id,
            ])
            ->with('setting')
            ->all();

        $result = [];

        foreach ($preferences as $preference) {
            $result[] = [
                'id_preference' => $preference->id_preference,
                'id_setting' => $preference->id_setting,
                'setting_name' => $preference->setting->name ?? null,
                'value' => $preference->value,
            ];
        }

        return $result;
    }

    /**
     * GET /api/preferences/{id}
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
     * POST /api/preferences
     */
    public function actionCreate()
    {
        $body = Yii::$app->request->bodyParams;

        $model = new Preference();
        $model->id_user = Yii::$app->user->id;
        $model->id_workspace = $body['id_workspace'] ?? null;
        $model->id_setting = $body['id_setting'] ?? null;
        $model->value = $body['value'] ?? null;

        if ($model->save()) {
            Yii::$app->response->statusCode = 201;
            return $model;
        }

        Yii::$app->response->statusCode = 422;
        return $model->errors;
    }

    /**
     * PUT /api/preferences/{id}
     */
    public function actionUpdate($id)
    {
        $model = Preference::findOne([
            'id_preference' => $id,
            'id_user' => Yii::$app->user->id,
        ]);

        if (!$model) {
            throw new NotFoundHttpException('Preference not found.');
        }

        $model->load(Yii::$app->request->bodyParams, '');

        if ($model->save()) {
            return $model;
        }

        Yii::$app->response->statusCode = 422;
        return $model->errors;
    }

    /**
     * DELETE /api/preferences/{id}
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