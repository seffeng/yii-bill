<?php

namespace zxf\models\entities;

use Yii;
use zxf\components\ActiveRecord;
use zxf\models\queries\UserLoginLogQuery;
use yii\helpers\ArrayHelper;
use zxf\models\services\ConstService;
use zxf\models\services\FunctionService;

class UserLoginLog extends ActiveRecord {

    /**
     * 登录
     * ull_type
     */
    const TYPE_LOGIN   = 1;
    /**
     * 登出
     * ull_type
     */
    const TYPE_LOGOUT  = 2;

    /**
     * 成功
     * ull_result
     */
    const RESULT_OK    = 1;
    /**
     * 失败
     * ull_result
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
     * ull_isdel
     */
    const DEL_YET = 1;
    /**
     * 已删除
     * ull_isdel
     */
    const DEL_NOT = 2;

    /**
     * 表名
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @return string
     */
    public static function tableName() {
        return '{{%user_login_log}}';
    }

    /**
     * 重写 find()
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @return object|mixed
     */
    public static function find() {
        return Yii::createObject(UserLoginLogQuery::className(), [get_called_class()]);
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
            'ull_id'      => 'ID',
            'u_id'        => '用户',
            'ull_result'  => '结果',
            'ull_content' => '内容',
            'ull_addtime' => '时间',
            'ull_addip'   => 'IP',
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
            $this->ull_addtime = THIS_TIME;
            $this->ull_addip   = ip2long(FunctionService::getUserIP());
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
     * 关联管理员资料
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @return \yii\db\ActiveQuery
     */
    public function getUserInfo() {
        return $this->hasOne(UserInfo::className(), ['u_id' => 'u_id']);
    }
}