<?php
/**
 * 配置文件
 */

$config = [
    'id'    => APP_NAME,
    'name'  => '我的账单',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'zxf\web\bill\controllers',
    'components'  => [
        'request' => [
            'class' => 'zxf\components\WebRequest',
            'cookieValidationKey' => 'abcdefghijklmnopqrstuvbill',
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'zxf\models\entities\User',
        ],
        'errorHandler'    => [
            'errorAction' => '/site/error',
        ],
        'assetManager' => [
            'basePath' => '@webroot/assets',
            'baseUrl'  => '@web/assets',
            'bundles'  => [
                'yii\web\JqueryAsset' => [
                    'js' => ['jquery.min.js'],
                    'jsOptions' => [
                        'position' => \yii\web\View::POS_HEAD,
                    ]
                ],
            ],
        ],
    ],
    'params' => include(__DIR__ . '/params.php'),
];

if (YII_DEBUG) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*'],
    ];
}
return $config;