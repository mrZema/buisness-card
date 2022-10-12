<?php

use yii\helpers\Html;
use common\widgets\Alert;
use yii\bootstrap4\ActiveForm;

/* @var $loginForm frontend\forms\auth\LoginForm */

$this->title = 'LogIn';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-wrapper d-flex flex-column">

    <div class="mx-3 mt-3">
        <?= Alert::widget() ?>
    </div>

    <div class="d-flex justify-content-center flex-grow-1">
        <div class="login-box align-self-center">
            <div class="login-logo">
                <h1 class="text-dark"><?= $this->title ?></h1>
            </div>

            <div class="card elevation-4">
                <div class="card-body login">
                    <p class="login-box-msg">Sign in to start earn</p>

                    <?php $form = ActiveForm::begin(['id' => 'login-form']) ?>

                    <?= $form->field($loginForm, 'username', [
                        'options' => ['class' => 'form-group has-feedback'],
                        'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>',
                        'template' => '{beginWrapper}{input}{error}{endWrapper}',
                        'wrapperOptions' => ['class' => 'input-group mb-3']
                    ])
                        ->label(false)
                        ->textInput(['placeholder' => $loginForm->getAttributeLabel('username')]) ?>

                    <?= $form->field($loginForm, 'password', [
                        'options' => ['class' => 'form-group has-feedback'],
                        'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>',
                        'template' => '{beginWrapper}{input}{error}{endWrapper}',
                        'wrapperOptions' => ['class' => 'input-group mb-3']
                    ])
                        ->label(false)
                        ->passwordInput(['placeholder' => $loginForm->getAttributeLabel('password')]) ?>

                    <div class="row">
                        <div class="col-8">
                            <?= $form->field($loginForm, 'rememberMe')->checkbox() ?>
                        </div>
                        <div class="col-4">
                            <?= Html::submitButton('Sign In', ['class' => 'btn btn-primary btn-block']) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
                <!-- /.login-card-body -->
            </div>

        </div>
    </div>
</div>
