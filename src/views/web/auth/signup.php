<?php

use portalium\site\bundles\AppAsset;
use yii\helpers\Html;
use yii\captcha\Captcha;
use portalium\theme\widgets\ActiveForm;
use portalium\site\Module;
use portalium\storage\models\Storage;

$this->title = Module::t('Signup');
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

<div class="site-signup">

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

                        <h1 class="h3 mb-3 fw-normal text-center"><?= Html::encode($this->title) ?></h1>

                        <?php $form = ActiveForm::begin([
                            'id' => 'form-signup',
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

                        <?= $form->field($model, 'username', ['options' => ['class' => 'form-attribute mb-3 row']])
                            ->textInput(['autofocus' => true, 'class' => 'form-control form-control-lg', 'placeholder' => Module::t('Username')]) ?>

                        <?= $form->field($model, 'email', ['options' => ['class' => 'form-attribute mb-3 row']])
                            ->textInput(['class' => 'form-control form-control-lg', 'placeholder' => Module::t('Email')]) ?>

                        <?= $form->field($model, 'password', ['options' => ['class' => 'form-attribute mb-3 row']])
                            ->passwordInput(['class' => 'form-control form-control-lg', 'placeholder' => Module::t('Password')]) ?>

                        <?= $form->field($model, 'verifyCode')->widget(
                            \himiklab\yii2\recaptcha\ReCaptcha3::className(),
                            [
                                'siteKey' => '6LdtOVspAAAAAGGnMu_yPK2hlyyNAjmiQJz0v7Ws',
                                'action' => 'signup',
                            ]
                        ) ?>

                        <div class="d-grid" style="margin-left:10px; margin-right:10px;">
                            <?= Html::submitButton(Module::t('Signup'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </div>
             <div class="col-lg-6 d-flex justify-content-center">
            <?php 
                $signupImageId = Yii::$app->setting->getValue('app::login_image');
                if ($signupImageId) {
                    $storage = Storage::findOne($signupImageId);
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

                        <h1 class="h3 mb-3 fw-normal text-center"><?= Html::encode($this->title) ?></h1>

                        <?php $form = ActiveForm::begin([
                            'id' => 'form-signup',
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

                        <?= $form->field($model, 'username', ['options' => ['class' => 'form-attribute mb-3 row']])
                            ->textInput(['autofocus' => true, 'class' => 'form-control form-control-lg', 'placeholder' => Module::t('Username')]) ?>

                        <?= $form->field($model, 'email', ['options' => ['class' => 'form-attribute mb-3 row']])
                            ->textInput(['class' => 'form-control form-control-lg', 'placeholder' => Module::t('Email')]) ?>

                        <?= $form->field($model, 'password', ['options' => ['class' => 'form-attribute mb-3 row']])
                            ->passwordInput(['class' => 'form-control form-control-lg', 'placeholder' => Module::t('Password')]) ?>

                        <?= $form->field($model, 'verifyCode')->widget(
                            \himiklab\yii2\recaptcha\ReCaptcha3::className(),
                            [
                                'siteKey' => '6LdtOVspAAAAAGGnMu_yPK2hlyyNAjmiQJz0v7Ws',
                                'action' => 'signup',
                            ]
                        ) ?>

                        <div class="d-grid" style="margin-left:10px; margin-right:10px;">
                            <?= Html::submitButton(Module::t('Signup'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>
