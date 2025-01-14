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