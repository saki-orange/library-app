<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => $_ENV['YII_COOKIE_VALIDATION_KEY'],
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                // ['class' => 'yii\rest\UrlRule', 'controller' => 'user'],
                // User
                'GET api/users' => 'user/index',
                'POST api/users' => 'user/create',
                'PUT api/users/<id>' => 'user/update',
                'DELETE api/users/<id>' => 'user/delete',
                // Book
                'GET api/books' => 'book/index',
                'POST api/books' => 'book/create',
                'PUT api/books/<id>' => 'book/update',
                'DELETE api/books/<id>' => 'book/delete',
                // BookSKU
                'GET api/book-sku' => 'book-sku/index',
                'POST api/book-sku' => 'book-sku/create',
                'PUT api/book-sku/<id>' => 'book-sku/update',
                'DELETE api/book-sku/<id>' => 'book-sku/delete',
                // Lending
                'GET api/lendings' => 'lending/index',
                'POST api/lendings' => 'lending/create',
                'DELETE api/lendings/<id>' => 'lending/delete',
                // Reservation
                'GET api/reservations' => 'reservation/index',
                'POST api/reservations' => 'reservation/create',
                'DELETE api/reservations/<id>' => 'reservation/delete',
                // Hold
                'GET api/holds' => 'hold/index',
                'POST api/holds' => 'hold/create',
                'DELETE api/holds/<id>' => 'hold/delete',
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
