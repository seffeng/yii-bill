<?php

namespace zxf\web\bill\controllers;

use Yii;
use zxf\web\bill\components\Controller;
use zxf\models\services\ConstService;
use zxf\models\entities\Bill;
use zxf\models\services\BillService;
use zxf\models\entities\UserLog;
use zxf\models\services\UserLogService;
use zxf\models\services\FunctionService;
use yii\helpers\Json;

class BillController extends Controller {

    /**
     * 列表
     * @author ZhangXueFeng
     * @date   2016年11月17日
     */
    public function actionIndex() {
        $request = Yii::$app->request;
        $page    = $request->get('page', $request->post('page', 1));
        $formModel = new Bill(['scenario' => ConstService::SCENARIO_SEARCH]);
        $formModel->load($request->get());
        $formModel->u_id = Yii::$app->getUser()->getId();
        $dataProvider = BillService::getList($formModel, $page);
        $params = [
            'formModel'    => $formModel,
            'typeText'     => ['' => '----'] + Bill::TYPE_TEXT,
            'dataProvider' => $dataProvider,
            'breadcrumb'   => ['账单列表  <span class="text-blue text-sm" adm="add" role="button">添加账单</span>', '账单管理', '账单列表']
        ];
        if (Yii::$app->request->isAjax) {
            $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('index', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            return $return;
        }
        return $this->render('index', $params);
    }

    /**
     * 统计图表
     * @author ZhangXueFeng
     * @date   2016年11月18日
     */
    public function actionChart() {
        $request = Yii::$app->request;
        $formModel = new Bill(['scenario' => ConstService::SCENARIO_SEARCH]);
        $formModel->load($request->get());

        !$formModel->pay_start_date &&  $formModel->pay_start_date = date('Y-m-01');
        !$formModel->pay_end_date &&  $formModel->pay_end_date = date('Y-m-d');
        $chartData = BillService::getChartData($formModel);
        $params = [
            'formModel'  => $formModel,
            'typeText'   => ['' => '----'] + Bill::TYPE_TEXT,
            'chartData'  => $chartData,
            'breadcrumb' => ['报表统计', '账单管理', '报表统计']
        ];
        if (Yii::$app->request->isAjax) {
            $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('chart', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            return $return;
        }
        return $this->render('chart', $params);
    }

    /**
     * 添加
     * @author ZhangXueFeng
     * @date   2016年11月17日
     */
    public function actionAdd() {
        $model = new Bill(['scenario' => 'insert']);
        $params = [
            'model'        => $model,
            'typeText'     => Bill::TYPE_TEXT,
            'breadcrumb'   => ['添加账单  <span class="text-blue text-sm" adm="list" role="button">账单列表</span>', '账单管理', '添加账单'],
        ];
        if (Yii::$app->request->isAjax) {
            $userId = Yii::$app->getUser()->getId();
            $post = Yii::$app->request->post();
            if (count($post) > 0) {
                if ($model->load($post) && $model->validate()) {
                    $model->u_id = $userId;
                    if ($model->save()) {
                        $return = ['r' => 1, 'm' => '添加账单成功！'];
                    } else {
                        $return = ['r' => 0, 'm' => '添加账单失败！'];
                    }
                } else {
                    $return = ['r' => 0, 'd' => null, 'm' => FunctionService::getErrorsForString($model)];
                }
                $logData = [
                    'u_id'    => $userId,
                    'type'    => UserLog::TYPE_ADD_BILL,
                    'result'  => $return['r'] == 1 ? UserLog::RESULT_OK : UserLog::RESULT_FAILD,
                    'content' => '添加账单:'.$return['m'].'[b_name='.$model->b_name.']'
                ];
                UserLogService::addLog($logData);
            } else {
                $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('add', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            }
            return $return;
        }
        return $this->render('add', $params);
    }

    /**
     * 修改
     * @author ZhangXueFeng
     * @date   2016年11月17日
     */
    public function actionEdit() {
        $id = Yii::$app->request->get('id', Yii::$app->request->post('id', 0));
        if ($id < 1) {
            return ['r' => 0, 'm' => '参数错误！'];
        }
        $userId = Yii::$app->getUser()->getId();
        $model  = BillService::getUserBillById($id, $userId);
        if (!$model) {
            return ['r' => 0, 'm' => '该账单不存在！'];
        }
        $params = [
            'model' => $model,
            'typeText'    => Bill::TYPE_TEXT,
            'breadcrumb'  => ['修改账单  <span class="text-blue text-sm" adm="list" role="button">账单列表</span>', '账单管理', '修改账单'],
        ];
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (count($post) > 0) {
                $modelDiff = [];
                $model->setScenario(ConstService::SCENARIO_UPDATE);
                if ($model->load($post) && $model->validate()) {
                    $modelDiff = FunctionService::modelDiff($model, ['b_name', 'b_price', 'b_type']);
                    if ($model->save()) {
                        $return = ['r' => 1, 'm' => '修改账单成功！'];
                    } else {
                        $return = ['r' => 1, 'm' => '修改账单失败！'];
                    }
                } else {
                    $return = ['r' => 0, 'm' => FunctionService::getErrorsForString($model)];
                }
                $logData = [
                    'u_id'    => $userId,
                    'type'    => UserLog::TYPE_EDIT_BILL,
                    'result'  => $return['r'] == 1 ? UserLog::RESULT_OK : UserLog::RESULT_FAILD,
                    'content' => '修改账单:'.$return['m'].'[b_name='.$model->b_name.']',
                    'detail'  => $modelDiff ? Json::encode($modelDiff) : ''
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
     * 删除
     * @author ZhangXueFeng
     * @date   2016年11月17日
     * @return array
     */
    public function actionDel() {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->get('id', Yii::$app->request->post('id', 0));
            $return = ['r' => 0, 'm' => '参数错误！'];
            if ($id > 0) {
                $b_name = '';
                $userId = Yii::$app->getUser()->getId();
                $model  = BillService::getUserBillById($id, $userId);
                if ($model) {
                    $b_name = $model->b_name;
                    $model->b_isdel = Bill::DEL_YET;
                    if ($model->save()) {
                        $return['r'] = 1;
                        $return['m'] = '删除账单成功！';
                    } else {
                        $return['m'] = '删除账单失败！';
                    }
                } else {
                    $return['m'] = '该账单不存在！';
                }
                $logData = [
                    'u_id'    => $userId,
                    'type'    => UserLog::TYPE_DEL_BILL,
                    'result'  => $return['r'] == 1 ? UserLog::RESULT_OK : UserLog::RESULT_FAILD,
                    'content' => '删除账单:'.$return['m'].'[b_name='.$model->b_name.']'
                ];
                UserLogService::addLog($logData);
            }
            return $return;
        }
        return $this->goHome();
    }
}