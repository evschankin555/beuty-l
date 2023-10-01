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

        'login' => 'pages/login',
        'signup' => 'pages/signup',
        'logout' => 'pages/logout',
        'auth' => 'pages/auth',
        'profile/settings' => 'profile/settings',
        'profile/update-avatar' => 'profile/update-avatar',
        'register/validate' => 'pages/register-validate',
        'reset-password' => 'pages/reset-password',
        'confirm-email' => 'pages/confirm-email',
        'restore' => 'pages/restore',
        'page/<page:\d+>' => '/base/home',
        'base' => 'pages/base',
        [
            'class' => 'yii\web\UrlRule',
            'pattern' => '<base:[\w_\/-]+>card-<hash>',
            'route' => 'pages/card',
            'suffix' => ''
        ],
        [
            'class' => 'yii\web\UrlRule',
            'pattern' => '<category:[\w_\/-]+>/<subcategory>/page/<page:\d+>',
            'route' => 'pages/subcategory',
            'suffix' => ''
        ],
        [
            'class' => 'yii\web\UrlRule',
            'pattern' => '<category:[\w_\/-]+>/page/<page:\d+>',
            'route' => 'pages/category',
            'suffix' => ''
        ],
        [
            'class' => 'yii\web\UrlRule',
            'pattern' => '<category:[\w_\/-]+>/<subcategory>',
            'route' => 'pages/subcategory',
            'suffix' => ''
        ],
        [
            'class' => 'yii\web\UrlRule',
            'pattern' => '<category:[\w_\/-]+>',
            'route' => 'pages/category',
            'suffix' => ''
        ],
        '404' => 'site/error',
    ],


];