<?php

use yii\helpers\Html;
use portalium\theme\widgets\ActiveForm;
use portalium\theme\widgets\Panel;
use portalium\site\Module;

/* @var $this yii\web\View */
/* @var $model portalium\content\models\Content */
/* @var $form yii\widgets\ActiveForm */

$this->title = Module::t('Edit Account');
$this->params['breadcrumbs'][] = ['label' => Module::t('Setting'), 'url' => ['setting/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(['method' => 'post', 'action' => ['profile/edit']]); ?>
<?php Panel::begin([
    'title' => Html::encode(Module::t('Edit Profile')),
]) ?>

<?= $form->field($modelProfile, 'first_name')->label(Module::t('First Name'))->textInput(['maxlength' => true]) ?>
<?= $form->field($modelProfile, 'last_name')->label(Module::t('Last Name'))->textInput(['maxlength' => true]) ?>
<?= $form->field($modelProfile, 'username')->textInput(['maxlength' => true]) ?>
<?= $form->field($modelProfile, 'email')->textInput(['maxlength' => true]) ?>
<?= $form->field($modelProfile, 'id_avatar')->label(Module::t('Avatar'))->widget("\portalium\storage\widgets\FilePicker", [
    'multiple' => 0,
    'attributes' => ['id_storage'],
    'name' => 'app::logo_wide',
    'isPicker' => true,
    'isJson' => false
]) ?>

    <div class="text-end">
        <?= Html::submitButton(Module::t('Save'), ['class' => 'btn btn-success']) ?>
    </div>

<?php Panel::end() ?>
<?php ActiveForm::end(); ?>

<?php $accessTokenForm = ActiveForm::begin(['method' => 'post', 'action' => ['profile/regenerate-token']]); ?>
<?php Panel::begin([
    'title' => Html::encode(Module::t('Keys')),
]) ?>

<?= $accessTokenForm->field($modelProfile, 'access_token', [
    'template' => '{label}<div class="col-sm-10"><div class="input-group">{input}
        <span class="input-group-text" title="' . Module::t('Show/Hide') . '" id="toggleAccessToken" style="cursor: pointer">
            <i class="fa fa-eye-slash" id="eyeIcon"></i>
        </span>
        <a href="regenerate-token?id=' . $modelProfile->id.'" 
           data-method="post" 
           data-confirm="' . Module::t('The token will be refreshed. This may affect existing API connections. Do you approve?') . '" 
           class="input-group-text" 
           title="' . Module::t('Regenerate') . '" 
           id="regenerateTokenButton" 
           style="cursor: pointer">
            <i class="fa fa-refresh"></i>
        </a>
    </div>{error}</div>',
    'labelOptions' => ['class' => 'col-sm-2 col-form-label'],
])->textInput([
    'readonly' => true,
    'id' => 'accessTokenInput',
    'type' => 'password',
]) ?>

<?php Panel::end() ?>
<?php ActiveForm::end(); ?>

<?php
$changePassForm = ActiveForm::begin(['method' => 'post', 'action' => ['profile/edit-password']]); ?>
<?php Panel::begin([
    'title' => Html::encode(Module::t('Change Password')),
    'actions' => [
        'header' => [],
        'footer' => [
            Html::submitButton(Module::t('Save'), ['class' => 'btn btn-success']),
        ]
    ],
]) ?>


<?= $changePassForm->field($modelPassword, 'old_password')->label(Module::t('Current Password'))->passwordInput(['class' => 'form-control form-control-lg']) ?>
<?= $changePassForm->field($modelPassword, 'password')->passwordInput(['class' => 'form-control form-control-lg']) ?>

<?php Panel::end() ?>
<?php ActiveForm::end(); ?>