<?php

use portalium\site\bundles\AppAsset;
use yii\helpers\Html;
use portalium\theme\widgets\ActiveForm;
use portalium\site\Module;
use portalium\site\models\LoginForm;
use portalium\storage\models\Storage;

$this->title = Module::t('Login');
AppAsset::register($this);

$loginLayout = Yii::$app->setting->getValue('login::layout', 'single-column');

$logoSquareId = Yii::$app->setting->getValue('app::logo_square');
$logoUrl = null;
if ($logoSquareId) {
    $storage = Storage::findOne($logoSquareId);
    if ($storage && $storage->fileExists()) {
        $logoUrl = $storage->getFilePath();
    }
}
?>

<div class="site-login">

    <?php if ($loginLayout === 'two-column'): ?>
    <div class="row" style="max-height: 100vh; display: flex; align-items: center;">
        <div class="col-lg-6 d-flex justify-content-center">
            <div class="card shadow-sm" style="width: 400px;">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <?php if ($logoUrl): ?>
                            <?= Html::img($logoUrl, [
                                'alt' => 'Logo',
                                'style' => 'max-width: 150px; height: auto; margin-bottom: 15px;'
                            ]) ?>
                        <?php endif; ?>
                    </div>

                    <h1 class="h3 mb-3 fw-normal text-center"><?= Html::encode(Module::t('Login')) ?></h1>

                    <?php $form = ActiveForm::begin([
                            'id' => 'login-form',
                            'options' => ['class' => 'form-horizontal'],
                            'fieldConfig' => [
                                'horizontalCssClasses' => [
                                    'label' => 'col-sm-4',
                                    'wrapper' => 'col-sm-8',
                                ],
                                'template' => "{input}\n{hint}\n{error}",
                                'labelOptions' => ['style' => 'margin-top: 10px;'],
                            ],
                        ]); ?>

                        <?= $form->field($model, 'username', ['options'=>['class' => 'form-attribute mb-3 row']])
                            ->textInput(['autofocus' => true, 'class' => 'form-control form-control-lg', 'placeholder' => Module::t('Username')]) ?>

                        <?= $form->field($model, 'password', ['options'=>['class' => 'form-attribute mb-3 row']])
                            ->passwordInput(['class' => 'form-control form-control-lg', 'placeholder' => Module::t('Password')]) ?>

                        <div class="row form-attribute">
                            <div class="col-6" style="padding-right:0px">
                                <?= Html::a(Module::t('Forgot Password!'), ['/site/auth/request-password-reset'], ['style' => 'margin-left: -10px']) ?>
                            </div>
                            <div class="col-6" style="padding-right:0px; margin-left:-13px;">
                                <?= $form->field($model, 'rememberMe', ['options' => ['style' => 'margin-top:0px; float:right;']])
                                    ->checkbox(['template' => "<div style='padding-left:0px;padding-top:-15px;'>\n{input} {label}\n</div>"])
                                    ->label(Module::t('Remember Me'), ['style' => 'margin-top: 0px;']) ?>
                            </div>
                        </div>

                        <div class="d-grid mb-3 form-attribute">
                            <?= Html::submitButton(Module::t('Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                        </div>

                        <?php if (Yii::$app->setting->getValue('form::signup')): ?>
                            <div class="d-grid mb-3 form-attribute">
                                <?= Html::a(Module::t('Signup'), ['/site/auth/signup'], ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
                            </div>
                        <?php endif; ?>

                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-flex justify-content-center">
            <?php 
            $loginImageId = Yii::$app->setting->getValue('app::login_image');
            if ($loginImageId) {
                $storage = Storage::findOne($loginImageId);
                if ($storage && $storage->fileExists()) {
                    $imgUrl = $storage->getFilePath();
                    echo Html::img($imgUrl, [
                        'style' => 'display: block; height: 75vh; max-width: 100%; max-height: auto;'
                    ]);
                }
            }
            ?>
        </div>
    </div>

    <?php else: ?>
        <div class="row justify-content-center" style="max-height: 100vh;">
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">

                        <div class="text-center mb-3">
                            <?php if ($logoUrl): ?>
                                <?= Html::img($logoUrl, [
                                    'alt' => 'Logo',
                                    'class' => 'img-fluid',
                                    'style' => 'max-width: 150px; height: auto; margin-bottom: 15px;'
                                ]) ?>
                            <?php endif; ?>
                        </div>

                        <h1 class="h3 mb-3 fw-normal text-center"><?= Html::encode(Module::t('Login')) ?></h1>

                        <?php $form = ActiveForm::begin([
                            'id' => 'login-form',
                            'options' => ['class' => 'form-horizontal'],
                            'fieldConfig' => [
                                'horizontalCssClasses' => [
                                    'label' => 'col-sm-4',
                                    'wrapper' => 'col-sm-8',
                                ],
                                'template' => "{input}\n{hint}\n{error}",
                                'labelOptions' => ['style' => 'margin-top: 10px;'],
                            ],
                        ]); ?>

                        <?= $form->field($model, 'username', ['options'=>['class' => 'form-attribute mb-3 row']])
                            ->textInput(['autofocus' => true, 'class' => 'form-control form-control-lg', 'placeholder' => Module::t('Username')]) ?>

                        <?= $form->field($model, 'password', ['options'=>['class' => 'form-attribute mb-3 row']])
                            ->passwordInput(['class' => 'form-control form-control-lg', 'placeholder' => Module::t('Password')]) ?>

                        <div class="row form-attribute">
                            <div class="col-6" style="padding-right:0px">
                                <?= Html::a(Module::t('Forgot Password!'), ['/site/auth/request-password-reset'], ['style' => 'margin-left: -10px']) ?>
                            </div>
                            <div class="col-6" style="padding-right:0px; margin-left:-13px;">
                                <?= $form->field($model, 'rememberMe', ['options' => ['style' => 'margin-top:0px; float:right;']])
                                    ->checkbox(['template' => "<div style='padding-left:0px;padding-top:-15px;'>\n{input} {label}\n</div>"])
                                    ->label(Module::t('Remember Me'), ['style' => 'margin-top: 0px;']) ?>
                            </div>
                        </div>

                        <div class="d-grid mb-3 form-attribute">
                            <?= Html::submitButton(Module::t('Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                        </div>

                        <?php if (Yii::$app->setting->getValue('form::signup')): ?>
                            <div class="d-grid mb-3 form-attribute">
                                <?= Html::a(Module::t('Signup'), ['/site/auth/signup'], ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
                            </div>
                        <?php endif; ?>

                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>