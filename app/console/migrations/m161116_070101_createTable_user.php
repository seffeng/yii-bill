<?php

use yii\db\Migration;
use zxf\models\entities\User;

class m161116_070101_createTable_user extends Migration
{
    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeUp()
     */
    public function safeUp() {
        $tableUser = User::tableName();
        $tableInfo  = $this->getDb()->getTableSchema($tableUser);
        if (!$tableInfo) {
            $this->createTable($tableUser, [
                'u_id'       => 'BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'用户ID[自增]\'',
                'u_username' => 'VARCHAR(20) NOT NULL COMMENT \'用户名\'',
                'u_password' => 'VARCHAR(72) NOT NULL COMMENT \'密码\'',
                'ug_id'      => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'用户组ID\'',
                'u_status'   => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT \''. User::STATUS_ON .'\' COMMENT \'状态[1-启用,2-停用]\'',
                'u_isdel'    => 'TINYINT(1) UNSIGNED UNSIGNED NOT NULL DEFAULT \''. User::DEL_NOT .'\' COMMENT \'是否删除[1-是,2-否]\'',
                'u_addtime'  => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'添加时间\'',
                'u_addip'    => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'添加IP\'',
                'u_lasttime' => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'最后更新时间\'',
                'u_lastip'   => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'最后更新IP\'',
                'PRIMARY KEY (`u_id`)',
                'KEY `ug_id` (`ug_id`)',
                'KEY `u_username` (`u_username`)',
            ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=\'用户\'');
        }
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeDown()
     */
    public function safeDown() {
        $tableUser = User::tableName();
        if ($this->getDb()->getTableSchema($tableUser)) {
            $this->dropTable($tableUser);
        }
    }
}
