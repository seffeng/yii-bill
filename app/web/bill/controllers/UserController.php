<?php

namespace zxf\web\bill\controllers;

use Yii;
use zxf\web\bill\components\Controller;
use zxf\models\entities\UserLog;
use zxf\models\services\UserLogService;
use zxf\models\services\ConstService;
use zxf\models\entities\UserLoginLog;
use zxf\models\services\UserLoginLogService;
use zxf\models\entities\UserInfo;
use zxf\models\services\FunctionService;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class UserController extends Controller {

    /**
     * 修改资料
     * @author ZhangXueFeng
     * @date   2016年11月16日
     */
    public function actionEdit() {
        $model = Yii::$app->getUser()->getIdentity();
        $model->setScenario(ConstService::SCENARIO_UPDATE);
        $userInfo = ArrayHelper::getValue($model, 'userInfo') ?: new UserInfo();
        $params = [
            'model'        => $model,
            'userInfo'     => $userInfo,
            'userGroup'    => [],
            'breadcrumb'   => ['修改资料 ', '用户中心', '修改资料'],
        ];
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (count($post) > 0) {
                $modelDiff = $userInfoDiff = [];
                if ($model->load($post) && $userInfo->load($post) && $model->validate() && $userInfo->validate()) {
                    $modelDiff = FunctionService::modelDiff($model, ['u_username', 'ug_id', 'u_status']);
                    if ($model->save()) {
                        $userInfo->u_id = $model->u_id;
                        $userInfoDiff = FunctionService::modelDiff($userInfo, ['ui_name', 'ui_phone', 'ui_email']);
                        $userInfo->save();
                        $return = ['r' => 1, 'm' => '修改资料成功！'];
                    } else {
                        $return = ['r' => 0, 'm' => '修改资料失败！'];
                    }
                } else {
                    $return = ['r' => 0, 'd' => null, 'm' => FunctionService::getErrorsForString($model).' '.FunctionService::getErrorsForString($userInfo)];
                }
                $diff = ArrayHelper::merge($modelDiff, $userInfoDiff);
                $logData = [
                    'u_id'   => Yii::$app->getUser()->getId(),
                    'type'    => UserLog::TYPE_EDIT_USER,
                    'result'  => $return['r'] == 1 ? UserLog::RESULT_OK : UserLog::RESULT_FAILD,
                    'content' => '修改资料:'.$return['m'].'[u_username='.$model->u_username.']',
                    'detail'  => $diff ? Json::encode($diff) : ''
                ];
                UserLogService::addLog($logData);
            } else {
                $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('edit', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            }
            return $return;
        }
        return $this->render('edit', $params);
    }

    /**
     * 日志
     * @author ZhangXueFeng
     * @date   2016年11月16日
     */
    public function actionLog() {
        $request = Yii::$app->request;
        $page    = $request->get('page', $request->post('page', 1));
        $formModel = new UserLog(['scenario' => ConstService::SCENARIO_SEARCH]);
        $formModel->load($request->get());
        $formModel->u_id = Yii::$app->getUser()->getId();
        $dataProvider = UserLogService::getList($formModel, $page);
        $params = [
            'formModel'    => $formModel,
            'resultText'   => ['' => '----'] + UserLog::RESULT_TEXT,
            'dataProvider' => $dataProvider,
            'breadcrumb'   => ['日志列表 ', '用户中心', '日志列表']
        ];
        if (Yii::$app->request->isAjax) {
            $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('log', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            return $return;
        }
        return $this->render('log', $params);
    }

    /**
     * 登录日志
     * @author ZhangXueFeng
     * @date   2016年11月16日
     */
    public function actionLoginLog() {
        $request = Yii::$app->request;
        $page    = $request->get('page', $request->post('page', 1));
        $formModel = new UserLoginLog(['scenario' => ConstService::SCENARIO_SEARCH]);
        $formModel->load($request->get());
        $formModel->u_id = Yii::$app->getUser()->getId();
        $dataProvider = UserLoginLogService::getList($formModel, $page);
        $params = [
            'formModel'    => $formModel,
            'resultText'   => ['' => '----'] + UserLoginLog::RESULT_TEXT,
            'dataProvider' => $dataProvider,
            'breadcrumb'   => ['登录日志列表 ', '用户中心', '登录日志列表']
        ];
        if (Yii::$app->request->isAjax) {
            $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('login-log', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            return $return;
        }
        return $this->render('login-log', $params);
    }
}