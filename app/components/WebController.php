<?php
/**
 * 全局控制器
 */

namespace zxf\components;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class WebController extends Controller {

    /**
     *
     * {@inheritDoc}
     * @see \yii\web\Controller::beforeAction()
     */
    public function beforeAction($action) {
        if (parent::beforeAction($action)) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
            }
            return TRUE;
        }
        return FALSE;
    }
}