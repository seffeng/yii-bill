<?php

namespace zxf\web\bill\controllers;

use Yii;
use zxf\web\bill\components\Controller;
use zxf\models\services\UserService;
use zxf\models\entities\UserLoginLog;
use zxf\models\services\UserLoginLogService;

class SiteController extends Controller {

    /**
     * 首页
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @return array
     */
    public function actionIndex() {
        if (Yii::$app->request->isAjax) {
            $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('index'), 'breadcrumb' => ['首页', '用户中心', '首页']], 'm' => ''];
            return $return;
        }
        return $this->render('index');
    }

    /**
     * 登录
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @return \yii\web\Response|array
     */
    public function actionLogin() {
        if (UserService::isLogin()) {
            return $this->goHome();
        }
        if (Yii::$app->request->isAjax) {
            return UserService::login();
        }
        return $this->render('login');
    }

    /**
     * 注册
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @return string
     */
    public function actionRegister() {
        if (UserService::isLogin()) {
            return $this->goHome();
        }
        if (Yii::$app->request->isAjax) {
            return UserService::register();
        }
        return $this->render('register');
    }

    /**
     * 登出
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @return \yii\web\Response
     */
    public function actionLogout() {
        if (UserService::isLogin()) {
            $user = Yii::$app->getUser()->getIdentity();
            $logData = [
                'u_id'   => $user->u_id,
                'type'    => UserLoginLog::TYPE_LOGOUT,
                'result'  => UserLoginLog::RESULT_OK,
                'content' => '登出:[username='.$user->u_username.']'
            ];
            UserLoginLogService::addLog($logData);
            Yii::$app->user->logout();
        }
        return $this->redirect(Yii::$app->user->loginUrl);
    }

    /**
     * 错误提示
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @return string
     */
    public function actionError() {
        $exception = Yii::$app->getErrorHandler()->exception;
        if ($exception) {
            $status    = $exception->getCode() ?: 404;
            $message   = $status == 404 ? '页面不存在！' : $exception->getMessage();
        } else {
            $status    = 404;
            $message   = '页面不存在！';
        }
        if (Yii::$app->request->isAjax) {
            return ['r' => 0, 'd' => $status, 'm' => $message];
        }
        return $this->render('error', ['status' => $status, 'message' => $message]);
    }
}