<?php

use yii\db\Migration;
use zxf\models\entities\UserGroup;

class m161116_072229_createTable_user_group extends Migration
{
    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeUp()
     */
    public function safeUp() {
        $tableUserGroup = UserGroup::tableName();
        $tableInfo = $this->getDb()->getTableSchema($tableUserGroup);
        if (!$tableInfo) {
            $this->createTable($tableUserGroup, [
                'ug_id'       => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'用户组ID[自增]\'',
                'ug_name'     => 'VARCHAR(50) NOT NULL COMMENT \'用户组名称\'',
                'ug_status'   => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT \''. UserGroup::STATUS_ON.'\' COMMENT \'状态[1-启用,2-停用]\'',
                'ug_isdel'    => 'TINYINT(1) UNSIGNED UNSIGNED NOT NULL DEFAULT \''. UserGroup::DEL_NOT .'\' COMMENT \'是否删除[1-是,2-否]\'',
                'ug_addtime'  => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'添加时间\'',
                'ug_addip'    => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'添加IP\'',
                'ug_lasttime' => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'最后更新时间\'',
                'ug_lastip'   => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'最后更新IP\'',
                'PRIMARY KEY (`ug_id`)',
                'KEY `ug_name` (`ug_name`)',
            ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=\'用户组\'');
        }
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeDown()
     */
    public function safeDown() {
        $tableUserGroup = UserGroup::tableName();
        if ($this->getDb()->getTableSchema($tableUserGroup)) {
            $this->dropTable($tableUserGroup);
        }
    }
}
