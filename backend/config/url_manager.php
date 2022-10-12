<?php

return [
    'class' => 'yii\web\UrlManager',
    'baseUrl' => $params['backendHostInfo'],
    'hostInfo' => $params['backendHostInfo'],
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        '' => 'site/index',
        '<_a:login|logout>' => 'auth/<_a>',
    ],
];
