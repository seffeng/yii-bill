<?php

namespace zxf\components;

use yii\web\Request;
use zxf\models\services\FunctionService;

class WebRequest extends Request {

    /**
     * 获取客户端IP
     * {@inheritDoc}
     * @see \yii\web\Request::getUserIP()
     */
    public function getUserIP() {
        return FunctionService::getUserIP();
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\web\Request::get()
     */
    public function get($name=NULL, $defaultValue=NULL, $filter=TRUE) {
        $gets = parent::get($name, $defaultValue);
        if ($filter && $gets) {
            return FunctionService::filterString($gets);
        }
        return $gets;
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\web\Request::post()
     */
    public function post($name=NULL, $defaultValue=NULL, $filter=TRUE) {
        $posts = parent::post($name, $defaultValue);
        if ($filter && $posts) {
            return FunctionService::filterString($posts);
        }
        return $posts;
    }
}