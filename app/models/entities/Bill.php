<?php

namespace zxf\models\entities;

use Yii;
use zxf\components\ActiveRecord;
use zxf\models\queries\BillQuery;
use yii\helpers\ArrayHelper;
use zxf\models\services\ConstService;
use zxf\models\services\FunctionService;

class Bill extends ActiveRecord {

    /**
     * 支出
     * b_type
     */
    const TYPE_EXPENSE  = 1;
    /**
     * 收入
     * b_type
     */
    const TYPE_INCOME   = 2;
    /**
     * 类型说明
     */
    const TYPE_TEXT = [
        self::TYPE_EXPENSE => '支出',
        self::TYPE_INCOME  => '收入',
    ];

    /**
     * 已删除
     * b_isdel
     */
    const DEL_YET = 1;
    /**
     * 未删除
     * b_isdel
     */
    const DEL_NOT = 2;

    /**
     * 表名
     * @author ZhangXueFeng
     * @date   2016年11月17日
     * @return string
     */
    public static function tableName() {
        return '{{%bill}}';
    }

    /**
     * 重写 find()
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @return mixed
     */
    public static function find() {
        return Yii::createObject(BillQuery::className(), [get_called_class()]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\base\Model::scenarios()
     */
    public function scenarios() {
        return ArrayHelper::merge(parent::scenarios(), [
            ConstService::SCENARIO_SEARCH => ['username', 'pay_start_date', 'pay_end_date', 'b_type', 'u_id', 'b_name'],
        ]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\base\Model::rules()
     */
    public function rules() {
        return [
            [['b_name', 'b_paytime', 'b_price'], 'required', 'on' => [ConstService::SCENARIO_INSERT, ConstService::SCENARIO_UPDATE], 'message' => ConstService::ERROR_RULES_REQUIRE],
            [['b_name', 'b_paytime', 'b_remark', 'b_price'], 'string', 'on' => [ConstService::SCENARIO_INSERT, ConstService::SCENARIO_UPDATE], 'message' => ConstService::ERROR_RULES_FORMAT],
            [['b_type'], 'integer', 'on' => [ConstService::SCENARIO_INSERT, ConstService::SCENARIO_UPDATE], 'message' => ConstService::ERROR_RULES_FORMAT],
        ];
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\db\ActiveRecord::attributes()
     */
    public function attributes() {
        return ArrayHelper::merge(parent::attributes(), [
            'pay_start_date', 'pay_end_date'
        ]);
    }

    /**
     * 
     * {@inheritDoc}
     * @see \yii\base\Model::attributeLabels()
     */
    public function attributeLabels() {
        return [
            'b_id'     => 'ID',
            'u_id'     => '用户',
            'b_name'   => '消费名目',
            'b_price'  => '金额',
            'b_type'   => '类型',
            'b_remark' => '备注',
            'b_isdel'      => '是否删除',
            'b_addtime'    => '添加时间',
            'b_addip'      => '添加IP',
            'b_paytime'    => '消费时间',
            'b_lasttime'   => '修改时间',
            'b_lastip'     => '修改IP',
            'pay_start_date' => '消费时间',
            'pay_end_date'   => '消费时间',
        ];
    }

    /**
     * 
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert) {
        $ipLong = ip2long(FunctionService::getUserIP());
        if ($insert) {
            $this->b_addtime = THIS_TIME;
            $this->b_addip   = $ipLong;
        }
        !is_numeric($this->b_paytime) && $this->b_paytime = strtotime($this->b_paytime);
        if ($this->b_price != $this->getOldAttribute('b_price')) {
            $this->b_price = bcmul($this->b_price, 100);
        }
        $this->b_lasttime = THIS_TIME;
        $this->b_lastip   = $ipLong;
        return parent::beforeSave($insert);
    }

    /**
     * 关联用户
     * @author ZhangXueFeng
     * @date   2016年11月17日
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['u_id' => 'u_id']);
    }

    /**
     * 关联资料
     * @author ZhangXueFeng
     * @date   2016年11月76日
     * @return \yii\db\ActiveQuery
     */
    public function getUserInfo() {
        return $this->hasOne(UserInfo::className(), ['u_id' => 'u_id']);
    }
}