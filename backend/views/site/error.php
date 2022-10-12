<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */

/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="content-wrapper d-flex justify-content-center">
    <div class="align-self-center">

        <div class="login-logo">
            <h1 class="text-dark"><?= $this->title ?></h1>
        </div>

        <div class="card elevation-4">
            <div class="card-body">
                <p class="login-box-msg">
                    <strong><?= nl2br(Html::encode($message)) ?></strong>
                </p>

                <div class="error-page">
                    <h2 class="headline text-info"><i class="fa fa-exclamation-triangle text-yellow"></i></h2>

                    <div class="error-content">
                        <p>
                            The above error occurred while the Web server was processing your request.
                            Please contact us if you think this is a server error. Thank you.
                            Meanwhile, you may <a href='<?= Yii::$app->frontendUrlManager->createUrl(['']) ?>'>return to
                                dashboard</a> or try using the search
                            form.
                        </p>

                        <form class='search-form'> <? //ToDo refactor form to send request on FrontEnd Search Action?>
                            <div class='input-group'>
                                <input type="text" name="search" class='form-control' placeholder="Search"/>

                                <div class="input-group-btn">
                                    <?= Html::submitButton('<i class="fa fa-search"></i>', ['class' => 'btn btn-primary']) ?>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
