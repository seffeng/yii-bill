<?php
/**
 * Login Asset
*/

namespace zxf\web\bill\components;

use yii\web\View;
use yii\web\AssetBundle;

class LoginAsset extends AssetBundle {

    public $basePath = '@webroot';

    public $baseUrl  = '@web';

    public $css = [
        'static/plugins/bootstrap/css/bootstrap.min.css',
        'static/plugins/font-awesome/css/font-awesome.min.css',
        'static/plugins/iCheck/square/blue.css',
        'static/css/AdminLTE.min.css',
        'static/css/default.css',
    ];

    public $js = [
        'static/plugins/bootstrap/js/bootstrap.min.js',
        'static/plugins/iCheck/icheck.min.js',
        'static/js/cls_global.js',
        'static/js/cls_ajax.js',
        'static/js/cls_menu.js',
        'static/js/cls_alert.js',
        'static/js/cls_login.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];

    public static function addScriptForIE($view) {
        $view->registerJsFile('@web/static/js/html5shiv.min.js', ['condition' => 'lte IE 9', 'position' => View::POS_HEAD, 'depends' => 'yii\web\JqueryAsset']);
        $view->registerJsFile('@web/static/js/respond.min.js', ['condition' => 'lte IE 9', 'position' => View::POS_HEAD, 'depends' => 'yii\web\JqueryAsset']);
    }
}