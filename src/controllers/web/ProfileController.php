<?php

namespace portalium\site\controllers\web;

use portalium\site\models\ProfileForm;
use portalium\site\models\ProfilePasswordForm;
use portalium\user\models\User;
use portalium\web\Controller as WebController;
use portalium\site\Module;
use Yii;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class ProfileController extends WebController
{
    public function actionEdit()
    {

        if (!Yii::$app->user->can('siteWebProfileEdit')) {
            throw new \yii\web\ForbiddenHttpException(Module::t('You are not allowed to access this page.'));
        }

        if (($user = User::findOne(Yii::$app->user->identity->id_user)) === null) {
            throw new \yii\web\NotFoundHttpException(Module::t('The requested page does not exist.'));
        }

        $modelProfile = new ProfileForm();
        $modelPassword = new ProfilePasswordForm();
        $profileJson = Yii::$app->request->post('ProfileForm', []);
        $user = User::findOne(Yii::$app->user->identity->id_user);
        $modelProfile->id = Yii::$app->user->identity->id_user;
        $modelProfile->username = $profileJson['username'] ?? $user->username;
        $modelProfile->first_name = $profileJson['first_name'] ?? $user->first_name;
        $modelProfile->last_name = $profileJson['last_name'] ?? $user->last_name;
        $modelProfile->email = $profileJson['email'] ?? $user->email;
        $modelProfile->id_avatar = $profileJson['id_avatar'] ?? $user->id_avatar;
        $modelProfile->access_token = $user->access_token;

        if ($modelProfile->filterPostData(Yii::$app->request->post('ProfileForm'))) {
            if ($modelProfile->updateUser()) {
                Yii::$app->session->addFlash('success', Module::t('Your profile has been successfully updated!'));
                return $this->redirect(['edit']);
            }
        }
        return $this->render('edit', [
            'modelProfile' => $modelProfile,
            'modelPassword' => $modelPassword,

        ]);
    }

    public function actionEditPassword()
    {

        if (!Yii::$app->user->can('siteWebProfileEditPassword')) {
            throw new \yii\web\ForbiddenHttpException(Module::t('You are not allowed to access this page.'));
        }

        if (($user = User::findOne(Yii::$app->user->identity->id_user)) === null) {
            throw new \yii\web\NotFoundHttpException(Module::t('The requested page does not exist.'));
        }

        $modelPassword = new  ProfilePasswordForm();
        $modelProfile = new ProfileForm();

        $modelProfile->load($user->attributes, '');

        if ($modelPassword->load($modelProfile->filterPostData(Yii::$app->request->post('ProfilePasswordForm')), '')) {
            if ($modelPassword->updatePassword()) {
                Yii::$app->session->addFlash('success', Module::t('Your password has been successfully updated'));
                $modelPassword = new ProfilePasswordForm();
            } else {
                Yii::$app->session->addFlash('error', Module::t('Your old Password information is incorrect!'));
                $modelPassword = new ProfilePasswordForm();
            }
            return $this->redirect(['edit']);
        }

        return $this->render('edit', [
            'modelPassword' => $modelPassword,
            'modelProfile' => $modelProfile,
        ]);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => ['regenerate-token' => ['POST']],
        ];
        return $behaviors;
    }

    /**
     * Regenerates the user's API key (access_token). Defaults to the current user when $id is
     * omitted; another user's record requires siteWebProfileRegenerateToken (admin).
     */
    public function actionRegenerateToken($id = null)
    {
        $user = User::findOne($id ?? Yii::$app->user->id);
        if ($user === null) {
            throw new NotFoundHttpException(Module::t('The requested page does not exist.'));
        }
        if (
            !Yii::$app->user->can('siteWebProfileRegenerateToken') &&
            !Yii::$app->user->can('siteWebProfileRegenerateTokenOwn', ['model' => $user])
        ) {
            throw new ForbiddenHttpException(Module::t('You are not allowed to access this page.'));
        }

        if (($user->access_token = Yii::$app->security->generateRandomString(32)) && ($user->save())) {
            Yii::$app->session->setFlash('success', Module::t('Your token has been successfully generated!'));
        } else {
            Yii::$app->session->setFlash('error', Module::t('Your token could not be generated!'));
        }

        return $this->redirect('edit');
    }
}
