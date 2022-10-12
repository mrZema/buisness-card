<?php

return [
    'class' => 'yii\web\UrlManager',
    'baseUrl' => $params['frontendHostInfo'],
    'hostInfo' => $params['frontendHostInfo'],
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        '' => 'site/index',
        'contact' => 'site/contact',
        'signup' => 'auth/signup/request',
        'signup/<_a:[\w-]+>' => 'auth/signup/<_a>',
        'reset' => 'auth/reset/request',
        '<_a:login|logout>' => 'auth/auth/<_a>',
    ],
];
