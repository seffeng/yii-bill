<?php
/**
 * 全局常量定义
 */

/* 简化常量  */
define('D_S',           DIRECTORY_SEPARATOR);

/* 程序根目录 */
define('ROOT_PATH',     preg_replace_callback('/[\/\\\\]+/', function($match){ return D_S; }, dirname(__FILE__)) . D_S);

/* vendor目录  */
define('VENDOR_PATH',   ROOT_PATH .'vendor'. D_S);

/* app目录  */
define('APP_PATH',      ROOT_PATH .'app'. D_S);

/* web app目录  */
define('APP_WEB_PATH',  ROOT_PATH .'app'. D_S . 'web' . D_S);

/* data目录  */
define('DATA_PATH',     ROOT_PATH .'data'. D_S);

/* runtime目录  */
define('RUNTIME_PATH',  ROOT_PATH .'runtime'. D_S);

/* 当前时间戳 */
define('THIS_TIME',     time());

/* 调试模式  */
define('YII_DEBUG',     TRUE);

/* 运行环境['dev', 'prod'] */
define('YII_ENV',       'dev');