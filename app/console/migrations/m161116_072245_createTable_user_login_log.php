<?php

use yii\db\Migration;
use zxf\models\entities\UserLoginLog;

class m161116_072245_createTable_user_login_log extends Migration
{
    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeUp()
     */
    public function safeUp() {
        $tableName = UserLoginLog::tableName();
        $tableInfo = $this->getDb()->getTableSchema($tableName);
        if (!$tableInfo) {
            $this->createTable($tableName, [
                'ull_id'       => 'BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'日志ID[自增]\'',
                'u_id'         => 'BIGINT(10) UNSIGNED NOT NULL DEFAULT \'0\'  COMMENT \'用户ID\'',
                'ull_type'     => 'TINYINT(3) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'日志类型[UserLoginLog::TYPE_]\'',
                'ull_result'   => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'操作结果[1-成功,2-失败]\'',
                'ull_content'  => 'VARCHAR(255) NOT NULL COMMENT \'日志内容\'',
                'ull_isdel'    => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT \''. UserLoginLog::DEL_NOT .'\' COMMENT \'是否删除[1-是,2-否]\'',
                'ull_addtime'  => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'操作时间\'',
                'ull_addip'    => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'操作IP\'',
                'PRIMARY KEY (`ull_id`)',
                'KEY `u_id` (`u_id`)',
            ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=\'用户登录日志表\'');
        }
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeDown()
     */
    public function safeDown() {
        $tableName = UserLoginLog::tableName();
        if ($this->getDb()->getTableSchema($tableName)) {
            $this->dropTable($tableName);
        }
    }
}
