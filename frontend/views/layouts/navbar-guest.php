<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <a class="navbar-brand" href="<?= Url::home()?>">
            <div style="width: 242px; border-right: 1px solid #4b545c">
                <img src="<?= Yii::getAlias('@assets')?>/img/logo/dark-160x160.png" width="30" height="30" class="align-top" alt="Logo" style="margin-left: .8rem;">
                <span class="brand-text font-weight-light d-none d-sm-inline-block"><?= $appName?></span>
            </div>
        </a>

        <?= $this->render('navbar-menu') ?>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <?= Html::a('Log In | Sign In', ['/login'], ['class' => 'nav-link', 'title' => 'Log In']) ?>
        </li>
    </ul>
</nav>
