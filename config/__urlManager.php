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
        'old' => '/base/old',
        'buy1' => '/base/buy1',
        'buy2' => '/base/buy2',
        'buy3' => '/base/buy3',
        'pay' => '/base/pay',
        'create-pay' => '/base/create-pay',
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
        'payment/notification' => 'payment/notification',
        'payment/success' => 'payment/success',
        'payment/fail' => 'payment/fail',
    ],
];
