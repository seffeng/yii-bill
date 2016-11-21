<?php

namespace zxf\models\services;

use Yii;
use yii\data\ActiveDataProvider;
use zxf\models\entities\Bill;
use yii\helpers\ArrayHelper;

class BillService {

    /**
     * 
     * @author ZhangXueFeng
     * @date   2016年11月17日
     * @param  mixed $form
     * @param  integer $page      当前页码
     * @param  integer $pageSize  每页显示数量
     * @return \yii\data\ActiveDataProvider
     */
    public static function getList($form=NULL, $page=1, $pageSize=10) {
        $query = Bill::find();
        if (isset($form->b_id) && $form->b_id > 0) {
            $query->byId($form->b_id);
        }
        if (isset($form->u_id) && $form->u_id > 0) {
            $query->byUid($form->u_id);
        }
        if (isset($form->b_name) && $form->b_name != '') {
            $query->byName($form->b_name);
        }
        if (isset($form->b_type) && $form->b_type > 0) {
            $query->byType($form->b_type);
        }
        if (isset($form->pay_start_date) && $form->pay_start_date != '') {
            $query->andWhere(['>=', 'b_paytime', strtotime($form->pay_start_date)]);
        }
        if (isset($form->pay_end_date) && $form->pay_end_date != '') {
            $query->andWhere(['<=', 'b_paytime', strtotime($form->pay_end_date) + 86400]);
        }
        $query->byIsDel();
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'page'     => $page - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'attributes' => ['b_id', 'b_paytime', 'b_addtime'],
                'defaultOrder' => ['b_id' => SORT_DESC]
            ]
        ]);
    }

    /**
     * 类型说明
     * @author ZhangXueFeng
     * @date   2016年11月17日
     * @param  integer $type
     * @return string
     */
    public static function getTypeText($type) {
        return ArrayHelper::getValue(Bill::TYPE_TEXT, $type, '-');
    }

    /**
     * 根据ID查询
     * @author ZhangXueFeng
     * @date   2016年11月17日
     * @param  integer $id
     * @return mixed
     */
    public static function getById($id) {
        return Bill::find()->byId($id)->byIsDel()->limit(1)->one();
    }

    /**
     * 根据ID查询
     * @author ZhangXueFeng
     * @date   2016年11月17日
     * @param  integer $id
     * @return mixed
     */
    public static function getUserBillById($id, $u_id) {
        return Bill::find()->byId($id)->byUid($u_id)->byIsDel()->limit(1)->one();
    }

    /**
     * 获取图表数据
     * @author ZhangXueFeng
     * @date   2016年11月18日
     * @param  mixed $form
     * @return array
     */
    public static function getChartData($form) {
        $date   = $expense = $income = [];
        $userId = Yii::$app->getUser()->getId();
        $start_time = strtotime($form->pay_start_date);
        $end_time   = strtotime($form->pay_end_date) + 86399;
        $expense_result = Bill::find()->select('b_price, b_paytime')->byUid($userId)->byType(Bill::TYPE_EXPENSE)->byIsDel()->andWhere(['>=', 'b_paytime', $start_time])->andWhere(['<', 'b_paytime', $end_time])->all();
        $income_result  = Bill::find()->select('b_price, b_paytime')->byUid($userId)->byType(Bill::TYPE_INCOME)->byIsDel()->andWhere(['>=', 'b_paytime', $start_time])->andWhere(['<', 'b_paytime', $end_time])->all();

        while(TRUE) {
            $date[] = date('Y-m-d', $start_time);
            $expense[date('Y-m-d', $start_time)] = 0;
            $income[date('Y-m-d', $start_time)]  = 0;
            $start_time += 86400;
            if ($start_time > $end_time) break;
        }

        if (FunctionService::isForeach($expense_result)) foreach ($expense_result as $val) {
            if (isset($expense[date('Y-m-d', $val->b_paytime)])) {
                $expense[date('Y-m-d', $val->b_paytime)] += $val->b_price / 100;
            } else {
                $expense[date('Y-m-d', $val->b_paytime)] = $val->b_price / 100;
            }
        }
        if (FunctionService::isForeach($income_result)) foreach ($income_result as $val) {
            if (isset($income[date('Y-m-d', $val->b_paytime)])) {
                $income[date('Y-m-d', $val->b_paytime)] += $val->b_price / 100;
            } else {
                $income[date('Y-m-d', $val->b_paytime)] = $val->b_price / 100;
            }
        }
        return [
            'date'    => $date,
            'income'  => array_values($income),
            'expense' => array_values($expense),
        ];
    }
}