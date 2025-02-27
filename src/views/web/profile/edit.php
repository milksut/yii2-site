<?php

use yii\helpers\Html;
use portalium\theme\widgets\ActiveForm;
use portalium\theme\widgets\Panel;
use portalium\site\Module;

/* @var $this yii\web\View */
/* @var $model portalium\content\models\Content */
/* @var $form yii\widgets\ActiveForm */

$context = $this->context;
$this->title = Module::t('Edit Account');
$this->params['breadcrumbs'][] = ['label' => Module::t('Setting'), 'url' => ['setting/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(['method' => 'post', 'action' => ['profile/edit']]); ?>
<?php Panel::begin([
    'title' => Html::encode(Module::t('Edit Profile')),
    'actions' => [
        'header' => [],
        'footer' => [
            Html::submitButton(Module::t('Save'), ['class' => 'btn btn-success']),
        ]
    ],
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

<?= $form->field($modelProfile, 'access_token', [
    'template' => '{label}<div class="col-sm-10"><div class="input-group">{input}
        <span class="input-group-text p-1" title="' . Module::t('Show/Hide') . '" id="toggleAccessToken" style="cursor: pointer; font-size: medium">
            <i class="fa fa-eye-slash" id="eyeIcon"></i>
        </span>
        <a href="regenerate-token?id=' . $modelProfile->id.'" 
           data-method="post" 
           data-confirm="' . Module::t('The token will be refreshed. This may affect existing API connections. Do you approve?') . '" 
           class="input-group-text p-1" 
           title="' . Module::t('Regenerate') . '" 
           id="regenerateTokenButton" 
           style="cursor: pointer; font-size: medium">
            <i class="fa fa-refresh"></i>
        </a>
    </div>{error}</div>',
    'labelOptions' => ['class' => 'col-sm-2 col-form-label'],
    'errorOptions' => ['class' => 'invalid-feedback'],
])->textInput([
    'maxlength' => true,
    'readonly' => true,
    'id' => 'accessTokenInput',
    'type' => 'password',
    'title' => Module::t('Access Token')
]) ?>


<?php Panel::end() ?>
<?php ActiveForm::end(); ?>

<?php
$form2 = ActiveForm::begin(['method' => 'post', 'action' => ['profile/edit-password']]); ?>
<?php Panel::begin([
    'title' => Html::encode(Module::t('Change Password')),
    'actions' => [
        'header' => [],
        'footer' => [
            Html::submitButton(Module::t('Save'), ['class' => 'btn btn-success']),
        ]
    ],
]) ?>


<?= $form2->field($modelPassword, 'old_password')->label(Module::t('Current Password'))->passwordInput(['class' => 'form-control form-control-lg']) ?>
<?= $form2->field($modelPassword, 'password')->passwordInput(['class' => 'form-control form-control-lg']) ?>

<?php Panel::end() ?>
<?php ActiveForm::end(); ?>