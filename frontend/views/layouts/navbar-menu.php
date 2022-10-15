<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>

<li class="nav-item d-none d-sm-inline-block">
    <a href="<?= Url::home()?>" class="nav-link">Home</a>
</li>

<li class="nav-item d-none d-sm-inline-block">
    <a href="/resume" class="nav-link">Resume</a>
</li>

<li class="nav-item dropdown">
    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Demo</a>
    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
        <li><a href="#" class="dropdown-item">Some action </a></li>
        <li><a href="#" class="dropdown-item">Some other action</a></li>
        <li><?= Html::a('Sign out', ['auth/auth/logout'], ['data-method' => 'post', 'class' => 'dropdown-item']) ?></li>

        <li class="dropdown-divider"></li>

        <!-- Level two dropdown-->
        <li class="dropdown-submenu dropdown-hover">
            <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Hover for action</a>
            <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                <li>
                    <a tabindex="-1" href="#" class="dropdown-item">level 2</a>
                </li>

                <!-- Level three dropdown-->
                <li class="dropdown-submenu">
                    <a id="dropdownSubMenu3" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">level 2</a>
                    <ul aria-labelledby="dropdownSubMenu3" class="dropdown-menu border-0 shadow">
                        <li><a href="#" class="dropdown-item">3rd level</a></li>
                        <li><a href="#" class="dropdown-item">3rd level</a></li>
                    </ul>
                </li>
                <!-- End Level three -->

                <li><a href="#" class="dropdown-item">level 2</a></li>
                <li><a href="#" class="dropdown-item">level 2</a></li>
            </ul>
        </li>
        <!-- End Level two -->
    </ul>
</li>
