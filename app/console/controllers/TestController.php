<?php

namespace zxf\console\controllers;

use zxf\components\ConsoleController;

class TestController extends ConsoleController {

    /**
     * 测试
     * @author ZhangXueFeng
     * @date   2016年10月10日
     */
    public function actionIndex() {
        var_dump(date('Y-m-d H:i:s', THIS_TIME));
    }
}