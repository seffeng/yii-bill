<?php

namespace zxf\models\entities;

use Yii;
use zxf\components\ActiveRecord;
use zxf\models\queries\UserGroupQuery;

class UserGroup extends ActiveRecord {

    /**
     * 启用
     * ug_status
     */
    const STATUS_ON   = 1;
    /**
     * 停用
     * ug_status
     */
    const STATUS_OFF  = 2;
    /**
     * 状态说明
     */
    const STATUS_TEXT = [
        self::STATUS_ON  => '启用',
        self::STATUS_OFF => '停用',
    ];

    /**
     * 已删除
     * ug_isdel
     */
    const DEL_YET = 1;
    /**
     * 未删除
     * ug_isdel
     */
    const DEL_NOT = 2;

    /**
     * 表名
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @return string
     */
    public static function tableName() {
        return '{{%user_group}}';
    }

    /**
     * 重写 find()
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @return object|mixed
     */
    public static function find() {
        return Yii::createObject(UserGroupQuery::className(), [get_called_class()]);
    }
}