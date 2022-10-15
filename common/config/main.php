<?php
$log_targets = require __DIR__ . '/log_targets.php';

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@common/runtime/cache',
            //'class' => 'yii\caching\MemCache',
            //'useMemcached' => true,
        ],
        'authManager' => 'yii\rbac\DbManager',
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => $log_targets,
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [
                        YII_ENV_DEV ? 'jquery.js' : 'jquery.min.js'
                    ]
                ],
                'yii\bootstrap4\BootstrapAsset' => [
                    'css' => [],//do not use exceed bootstrap file, cause adminLTE already consist it
                ],
                'yii\bootstrap4\BootstrapPluginAsset' => [
                    'js' => [
                        YII_ENV_DEV ? 'js/bootstrap.bundle.js' : 'js/bootstrap.bundle.min.js'
                    ]
                ],
                'common\assets\AppAsset' => [
                    'css' => [
                        'css/site_v.0.0.2.css'
                    ]
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'rbac-admin' => 'rbac.php'
                    ],
                ],
            ],
        ],
        'settings' => [
            'class' => 'yii2mod\settings\components\Settings',
        ],
    ],
    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module'
        ],
    ],
    'name' => 'Alex Che Demo',
];
