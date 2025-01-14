<?php

use portalium\site\bundles\AppAsset;
use yii\helpers\Html;
use portalium\theme\widgets\ActiveForm;
use portalium\site\Module;

$this->title = Module::t('Login');
AppAsset::register($this);
?>
<div class="site-login">
<div class="row justify-content-center">
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h3 mb-3 fw-normal text-center"><?= Html::encode($this->title) ?></h1>

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
                
                <?= $form->field($model, 'username', ['options'=>['class' => 'form-attribute mb-3 row']])->textInput(['autofocus' => true, 'class' => 'form-control form-control-lg', 'placeholder' => Module::t('Username')]) ?>
                <?= '<div class = "clearfix" style = "margin-top:2px;"></div>' .$form->field($model, 'password', ['options'=>['class' => 'form-attribute mb-3 row']])->passwordInput(['class' => 'form-control form-control-lg', 'placeholder' => Module::t('Password')]) ?>

                <div class="row form-attribute">
                    <div class="col-6" style="padding-right:0px">
                        <?= Html::a(Module::t('Forgot Password!'), ['/site/auth/request-password-reset'], ['style' => 'margin-left: -10px']) ?>
                    </div>

                    <div class="col-6" style="padding-right:0px; margin-left:-13px;">
                        <?=
                        $form->field($model, 'rememberMe', ['options' => ['style' => 'margin-top:0px; float:right;']])->checkbox([
                            'template' => "<div style='padding-left:0px;padding-top:-15px; '>\n{input} {label}\n</div>",
                        ])->label(Module::t('Remember Me'),['style' => 'margin-top: 0px;']) ?>
                    </div>

                </div>
                <div class="d-grid mb-3 form-attribute">
                    <?= '<div class = "clearfix"></div>' .Html::submitButton(Module::t('Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>

                </div>

                <?php if (Yii::$app->setting->getValue('form::signup')): ?>
                    <div class="d-grid mb-3 form-attribute">
                        <?= '<div class = "clearfix"></div>' .Html::a(Module::t('Signup'), ['/site/auth/signup'], ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
                    </div>
                <?php endif; ?>
                <?php ActiveForm::end(); ?>
            </div>
            </div>
        </div>
    </div>
</div>
