<?php

namespace portalium\site\controllers\web;

use portalium\site\models\ProfileForm;
use portalium\site\models\ProfilePasswordForm;
use portalium\user\models\User;
use portalium\web\Controller as WebController;
use portalium\site\Module;
use Yii;

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

        $modelProfile->load($user->attributes, '');

        if ($modelProfile->load($modelProfile->filterPostData((Yii::$app->request->post('ProfileForm'))))) {
            if ($modelProfile->updateUser()) {
                Yii::$app->session->addFlash('success', Module::t('Your profile has been successfully updated!'));
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
        }

        return $this->render('edit', [
            'modelPassword' => $modelPassword,
            'modelProfile' => $modelProfile,
        ]);
    }
}
