<?php
/**
 * 全局配置
 */

return [
    'vendorPath'  => VENDOR_PATH,
    'runtimePath' => RUNTIME_PATH . APP_NAME,
    'timeZone'    => 'Asia/Shanghai',
    'components'  => [
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn'   => 'mysql:host=localhost;dbname=yii_bill',
            'username' => 'user',
            'password' => 'pass',
            'charset'  => 'utf8',
            'tablePrefix' => 'yi_',
        ],
        'urlManager' => [
            'enablePrettyUrl' => TRUE,
            'enableStrictParsing' => FALSE,
            'showScriptName' => FALSE,
            'rules' => [],
        ],
    ],
    'params' => include(__DIR__ .'/params.php'),
];