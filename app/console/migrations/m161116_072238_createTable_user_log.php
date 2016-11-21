<?php

use yii\db\Migration;
use zxf\models\entities\UserLog;

class m161116_072238_createTable_user_log extends Migration
{
    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeUp()
     */
    public function safeUp() {
        $tableName = UserLog::tableName();
        $tableInfo = $this->getDb()->getTableSchema($tableName);
        if (!$tableInfo) {
            $this->createTable($tableName, [
                'ul_id'       => 'BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'日志ID[自增]\'',
                'u_id'        => 'BIGINT(10) UNSIGNED NOT NULL DEFAULT \'0\'  COMMENT \'用户ID\'',
                'ul_type'     => 'SMALLINT(6) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'日志类型[UserLog::TYPE_]\'',
                'ul_result'   => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'操作结果[1-成功,2-失败]\'',
                'ul_content'  => 'VARCHAR(255) NOT NULL COMMENT \'日志内容\'',
                'ul_detail'   => 'TEXT COMMENT \'日志详情[对应修改差异,json数据]\'',
                'ul_isdel'    => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT \''. UserLog::DEL_NOT .'\' COMMENT \'是否删除[1-是,2-否]\'',
                'ul_addtime'  => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'操作时间\'',
                'ul_addip'    => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'操作IP\'',
                'PRIMARY KEY (`ul_id`)',
                'KEY `u_id` (`u_id`)',
                'KEY `ul_type` (`ul_type`)',
            ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=\'用户日志表\'');
        }
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeDown()
     */
    public function safeDown() {
        $tableName = UserLog::tableName();
        if ($this->getDb()->getTableSchema($tableName)) {
            $this->dropTable($tableName);
        }
    }
}
