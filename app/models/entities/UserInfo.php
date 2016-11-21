<?php

namespace zxf\models\entities;

use zxf\components\ActiveRecord;
use zxf\models\services\ConstService;
use zxf\models\services\FunctionService;

class UserInfo extends ActiveRecord {

    /**
     * 表名
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @return string
     */
    public static function tableName() {
        return '{{%user_info}}';
    }

    /**
     * 
     * {@inheritDoc}
     * @see \yii\base\Model::attributeLabels()
     */
    public function attributeLabels() {
        return [
            'ui_id'       => 'ID',
            'u_id'        => '用户ID',
            'ui_name'     => '姓名',
            'ui_phone'    => '手机',
            'ui_email'    => '邮箱',
            'ui_lasttime' => '时间',
        ];
    }

    /**
     * 
     * {@inheritDoc}
     * @see \yii\base\Model::rules()
     */
    public function rules() {
        return [
            ['ui_name', 'required', 'message' => ConstService::ERROR_RULES_REQUIRE],
            [['ui_name', 'ui_email'], 'string', 'message' => ConstService::ERROR_RULES_FORMAT],
            ['ui_phone', 'integer', 'message' => ConstService::ERROR_RULES_FORMAT],
            ['ui_phone', 'checkAttribute'],
            ['ui_email', 'email', 'message' => ConstService::ERROR_RULES_FORMAT],
        ];
    }

    /**
     * 
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert) {
        $this->ui_lasttime = THIS_TIME;
        $this->ui_phone    = intval($this->ui_phone);
        return parent::beforeSave($insert);
    }

    /**
     * 字段检测
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @param  string $attribute
     */
    public function checkAttribute($attribute) {
        switch ($attribute) {
            case 'ui_phone' : {
                if (!FunctionService::checkData($this->ui_phone, 'mobile')) {
                    $this->addError($attribute, '手机 格式错误！');
                }
                break;
            }
        }
    }
}