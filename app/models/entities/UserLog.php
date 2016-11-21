<?php

namespace zxf\models\entities;

use Yii;
use zxf\components\ActiveRecord;
use zxf\models\queries\UserLogQuery;
use yii\helpers\ArrayHelper;
use zxf\models\services\ConstService;
use zxf\models\services\FunctionService;

class UserLog extends ActiveRecord {

    /**
     * 注册
     * ul_type
     */
    const TYPE_ADD_USER   = 1;
    /**
     * 修改
     * ul_type
     */
    const TYPE_EDIT_USER  = 2;
    /**
     * 删除
     * ul_type
     */
    const TYPE_DEL_USER   = 3;

    /**
     * 添加账单
     * ul_type
     */
    const TYPE_ADD_BILL   = 4;
    /**
     * 修改账单
     * ul_type
     */
    const TYPE_EDIT_BILL  = 5;
    /**
     * 删除账单
     * ul_type
     */
    const TYPE_DEL_BILL   = 6;

    /**
     * 成功
     * ul_result
     */
    const RESULT_OK    = 1;
    /**
     * 失败
     * ul_result
     */
    const RESULT_FAILD = 2;
    /**
     * 结果说明
     */
    const RESULT_TEXT  = [
        self::RESULT_OK    => '成功',
        self::RESULT_FAILD => '失败',
    ];

    /**
     * 已删除
     * ul_isdel
     */
    const DEL_YET = 1;
    /**
     * 已删除
     * ul_isdel
     */
    const DEL_NOT = 2;

    /**
     * 表名
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @return string
     */
    public static function tableName() {
        return '{{%user_log}}';
    }

    /**
     * 重写 find()
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @return object|mixed
     */
    public static function find() {
        return Yii::createObject(UserLogQuery::className(), [get_called_class()]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\base\Model::scenarios()
     */
    public function scenarios() {
        return ArrayHelper::merge(parent::scenarios(), [
            ConstService::SCENARIO_SEARCH => ['username', 'name', 'add_start_date', 'add_end_date', 'result', 'u_id'],
        ]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\db\ActiveRecord::attributes()
     */
    public function attributes() {
        return ArrayHelper::merge(parent::attributes(), [
            'add_start_date', 'add_end_date', 'result', 'name', 'username'
        ]);
    }

    /**
     * 
     * {@inheritDoc}
     * @see \yii\base\Model::attributeLabels()
     */
    public function attributeLabels() {
        return [
            'ul_id'      => 'ID',
            'u_id'       => '管理员',
            'ul_result'  => '结果',
            'ul_content' => '内容',
            'ul_addtime' => '时间',
            'ul_addip'   => 'IP',
            'username' => '用户名',
            'name'     => '姓名',
            'result'   => '结果',
            'add_start_date' => '时间',
        ];
    }

    /**
     * 
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert) {
        if ($insert) {
            $this->ul_addtime = THIS_TIME;
            $this->ul_addip   = ip2long(FunctionService::getUserIP());
        }
        return parent::beforeSave($insert);
    }

    /**
     * 关联用户
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['u_id' => 'u_id']);
    }

    /**
     * 关联资料
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @return \yii\db\ActiveQuery
     */
    public function getUserInfo() {
        return $this->hasOne(UserInfo::className(), ['u_id' => 'u_id']);
    }
}