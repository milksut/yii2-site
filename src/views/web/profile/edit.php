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
        <span class="input-group-text" title="' . Module::t('Copy') . '" id="copyAccessToken" style="cursor: pointer">
            <i class="fa fa-copy" id="copyIcon"></i>
        </span>
        <a href="regenerate-token?id=' . $modelProfile->id.'" 
           data-method="post" 
           data-confirm="' . Module::t('A new API key will be generated and the current one will stop working immediately. CLI sessions and scripts using it must log in again. Do you approve?') . '" 
           class="input-group-text" 
           title="' . Module::t('Regenerate') . '" 
           id="regenerateTokenButton" 
           style="cursor: pointer">
            <i class="fa fa-refresh"></i>
        </a>
    </div>{hint}{error}</div>',
    'labelOptions' => ['class' => 'col-sm-2 col-form-label'],
])->hint(Module::t('Use this key to sign in to the NovaVision CLI: {command}', ['command' => '<code>novavision login</code>']))->textInput([
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

<?php
/* ------------------------------------------------------------------
 * Phone Verification panel
 *
 * GET form → redirects to /auth/default/request-sms-verification
 * with ?tel_no=<value>. The controller reads that param and pre-fills
 * the request-code form. The actual number is only changed after a
 * successful SMS verification, so nothing is saved from this form.
 * ------------------------------------------------------------------ */
$verifyPhoneForm = ActiveForm::begin([
    'method'  => 'get',
    'action'  => ['/auth/default/request-sms-verification'],
    'options' => ['id' => 'verify-phone-form'],
]);
?>
<?php Panel::begin([
    'title' => Html::encode(Module::t('Phone Verification')),
]) ?>

<?php if ((int) $tel_no !== null && (int) $tel_no !== 0): ?>
    <div class="alert alert-success d-flex align-items-center">
        <i class="fa fa-check-circle me-2"></i>
        <div>
            <?= Module::t('Your phone number is verified:') ?>
            <strong><?= Html::encode($tel_no) ?></strong>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-warning d-flex align-items-center">
        <i class="fa fa-exclamation-triangle me-2"></i>
        <div><?= Module::t('Your phone number is not verified yet.') ?></div>
    </div>
<?php endif; ?>

<div class="mb-3">
    <label for="verify-sms-tel-no" class="form-label">
        <?= Module::t('Phone Number') ?>
    </label>
    <?= Html::textInput('tel_no', $tel_no, [
        'id'           => 'verify-sms-tel-no',
        'class'        => 'form-control',
        'placeholder'  => '+905551234567',
        'maxlength'    => 20,
        'autocomplete' => 'tel',
    ]) ?>
    <div class="form-text small text-muted">
        <?= Module::t('Use the international format, e.g. +905551234567') ?>
    </div>
</div>

<div class="text-end">
    <?= Html::submitButton(
        ((int) $tel_no !== null && (int) $tel_no !== 0)
            ? Module::t('Verify a Different Number')
            : Module::t('Send Verification Code'),
        ['class' => 'btn btn-success']
    ) ?>
</div>

<?php Panel::end() ?>
<?php ActiveForm::end(); ?>