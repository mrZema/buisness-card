<?php

use core\entities\rbac\DefaultRoles;
use yii\helpers\Url;
use backend\widgets\Menu;

/* @var $settings yii2mod\settings\components\Settings */

$settings = Yii::$app->settings;
$appName = $settings->get('FrontEnd', 'AppName') ?? 'YiiApp';

?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= Url::home() ?>" class="brand-link">
        <img src="<?= Yii::getAlias('@assets') ?>/img/logo/light-160x160.png" alt="Logo" class="brand-image"
             style="opacity: .8">
        <span class="brand-text font-weight-light"><?= $appName?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= $assetDir ?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">

            <?php
            echo Menu::widget([
                'items' => [
                    [
                        'group' => true,
                        'permissions' => DefaultRoles::ADMIN,
                        'items' => [
                            ['label' => 'Administration', 'header' => true],
                            [
                                'label' => 'User management',
                                'icon' => 'address-card',
                                'items' => [
                                    ['label' => 'Users', 'icon' => 'users', 'url' => ['/user/index']],
                                    ['label' => 'Roles', 'icon' => 'tag', 'url' => ['/rbac/role/index']],
                                ],
                            ],
                            ['label' => 'App Settings', 'icon' => 'cogs', 'url' => ['/settings/app/index']],
                        ],
                    ],
                    ['group' => true,
                        'permissions' => DefaultRoles::DEV,
                        'items' => [
                            ['label' => 'Development', 'header' => true],
                            [
                                'label' => 'Tools',
                                'icon' => 'wrench',
                                'items' => [
                                    ['label' => 'Error log', 'icon' => 'times', 'url' => ['/error-log/index']],
                                    ['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug/index']],
                                    ['label' => 'Gii', 'icon' => 'code', 'url' => ['/gii/index']],
                                ],
                            ],
                            [
                                'label' => 'App Settings',
                                'icon' => 'cogs',
                                'items' => [
                                    ['label' => 'Items', 'icon' => 'pencil-alt', 'url' => ['/settings/item/index']],
                                    ['label' => 'Sections', 'icon' => 'list', 'url' => ['/settings/section/index']],
                                ],
                            ]
                        ]
                    ],
                ]
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
