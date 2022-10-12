<?php

return [
    [
        'class' => 'core\entities\loggers\ErrorDbTarget',
        'levels' => ['error', 'warning'],
        'db' => 'db',
        'logTable' => '{{%log_errors}}',
        'except' => ['yii\web\HttpException*'],
    ],
];
