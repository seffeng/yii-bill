<?php

use yii\db\Migration;
use zxf\models\entities\UserInfo;

class m161116_071127_createTable_user_info extends Migration
{
    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeUp()
     */
    public function safeUp() {
        $tableUserInfo = UserInfo::tableName();
        $tableInfo = $this->getDb()->getTableSchema($tableUserInfo);
        if (!$tableInfo) {
            $this->createTable($tableUserInfo, [
                'ui_id'       => 'BIGINT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'自增ID\'',
                'u_id'        => 'BIGINT(10) UNSIGNED NOT NULL DEFAULT \'0\'  COMMENT \'用户ID\'',
                'ui_name'     => 'VARCHAR(50) NOT NULL COMMENT \'姓名\'',
                'ui_phone'    => 'BIGINT(11) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'手机号\'',
                'ui_email'    => 'VARCHAR(100) NOT NULL COMMENT \'邮箱\'',
                'ui_lasttime' => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'最后更新时间\'',
                'PRIMARY KEY (`ui_id`)',
                'UNIQUE `u_id` (`u_id`)',
                'KEY `ui_name` (`ui_name`)',
                'KEY `ui_phone` (`ui_phone`)',
                'KEY `ui_email` (`ui_email`)',
            ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=\'用户信息表\'');
        }
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeDown()
     */
    public function safeDown() {
        $tableUserInfo = UserInfo::tableName();
        if ($this->getDb()->getTableSchema($tableUserInfo)) {
            $this->dropTable($tableUserInfo);
        }
    }
}
