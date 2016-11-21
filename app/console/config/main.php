<?php
/**
 * 配置文件
 */

return [
    'id'    => APP_NAME,
    'name'  => 'console',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'zxf\console\controllers',
    'components' => [
    ],
    'params' => include(__DIR__ . '/params.php'),
];