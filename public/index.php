<?php
/**
 * 入口文件
 */

/* APP NAME */
define('APP_NAME',      'bill');

/* THIS PATH */
define('THIS_PATH',      preg_replace_callback('/[\/\\\\]+/', function($match){ return '/'; }, dirname(__FILE__)) .'/');

require(dirname(THIS_PATH) . '/env.php');

require(VENDOR_PATH .'autoload.php');
require(VENDOR_PATH .'yiisoft/yii2/Yii.php');
require(APP_PATH .'config/alias.php');

$config = yii\helpers\ArrayHelper::merge(
    require(APP_PATH . 'config/main.php'),
    require(APP_WEB_PATH . APP_NAME . D_S .'config/main.php')
);

(new yii\web\Application($config))->run();