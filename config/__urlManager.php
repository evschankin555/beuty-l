<?php
return [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'enableStrictParsing' => false,

    'normalizer' => [
        'class' => 'yii\web\UrlNormalizer',
        'action' => \yii\web\UrlNormalizer::ACTION_REDIRECT_PERMANENT,
    ],

    'rules' => [
        '/' => '/base/home',
        'admin-panel' => 'base/admin-panel',
        'create-payment' => 'base/create-payment',
        'get-payments' => 'base/get-payments',
        'download-all' => 'base/download-all',
        'login' => 'site/login',
        'logout' => 'site/logout',
        'contact' => 'site/contact',
        'about' => 'site/about',
        'base' => 'pages/base',
        '404' => 'site/error',
    ],
];
