<?php
return [
    //secret params which should be redefined in git ignored file params-local
    'cookieValidationKey' => '32 symbols cookie validation key',
    'cookieDomain' => '.your.domain',

    'frontendHostInfo' => 'http://your.domain',
    'backendHostInfo' => 'http://backend.your.domain',
    'assetsHostInfo' => 'http://assets.your.domain',

    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',

    //open params
    'user.passwordResetTokenExpire' => 3600,
    'user.rememberMeDuration' => 3600 * 24 * 30,
    'bsVersion' => '4.x',

    'assetsPath' => dirname(__DIR__) . '/web',
];
