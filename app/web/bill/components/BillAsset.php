<?php
/**
 * main Asset
*/

namespace zxf\web\bill\components;

use yii\web\View;
use yii\web\AssetBundle;

class BillAsset extends AssetBundle {

    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [
        'static/plugins/bootstrap/css/bootstrap.min.css',
        'static/plugins/bootstrap-switch/css/bootstrap-switch.min.css',
        'static/plugins/font-awesome/css/font-awesome.min.css',
        'static/plugins/jquery.datetimepicker/jquery.datetimepicker.min.css',
        'static/css/AdminLTE.min.css',
        'static/css/skins.min.css',
        'static/css/default.css',
    ];

    public $js = [
        'static/plugins/bootstrap/js/bootstrap.min.js',
        'static/plugins/bootstrap-switch/js/bootstrap-switch.min.js',
        'static/plugins/slimScroll/jquery.slimscroll.min.js',
        'static/plugins/jquery.datetimepicker/jquery.datetimepicker.full.min.js',
        'static/plugins/ueditor/ueditor.config.js',
        'static/plugins/ueditor/ueditor.all.min.js',
        'static/plugins/ueditor/lang/zh-cn/zh-cn.js',
        '/static/plugins/echarts/echarts.min.js',
        'static/js/app.min.js',
        'static/js/cls_global.js',
        'static/js/cls_ajax.js',
        'static/js/cls_menu.js',
        'static/js/cls_alert.js',
        'static/js/cls_form.js',
        'static/js/totop.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];

    public static function addScriptForIE($view) {
        $view->registerJsFile('@web/static/js/html5shiv.min.js', ['condition' => 'lte IE 9', 'position' => View::POS_HEAD, 'depends' => 'yii\web\JqueryAsset']);
        $view->registerJsFile('@web/static/js/respond.min.js', ['condition' => 'lte IE 9', 'position' => View::POS_HEAD, 'depends' => 'yii\web\JqueryAsset']);
    }
}