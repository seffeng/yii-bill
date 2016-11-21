<?php

use yii\db\Migration;
use zxf\models\entities\Bill;

class m161117_070628_createTable_bill extends Migration
{
    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeUp()
     */
    public function safeUp() {
        $tableBill = Bill::tableName();
        $tableInfo  = $this->getDb()->getTableSchema($tableBill);
        if (!$tableInfo) {
            $this->createTable($tableBill, [
                'b_id'       => 'BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'账单ID[自增]\'',
                'u_id'       => 'BIGINT(20) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'用户ID\'',
                'b_name'     => 'VARCHAR(50) NOT NULL COMMENT \'消费名目\'',
                'b_price'    => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'金额[单位：分]\'',
                'b_remark'   => 'VARCHAR(255) COMMENT \'备注\'',
                'b_type'     => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'类型[1-支出,2-收入]\'',
                'b_isdel'    => 'TINYINT(1) UNSIGNED UNSIGNED NOT NULL DEFAULT \''. Bill::DEL_NOT .'\' COMMENT \'是否删除[1-是,2-否]\'',
                'b_paytime'  => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'消费时间\'',
                'b_addtime'  => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'添加时间\'',
                'b_addip'    => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'添加IP\'',
                'b_lasttime' => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'最后更新时间\'',
                'b_lastip'   => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\' COMMENT \'最后更新IP\'',
                'PRIMARY KEY (`b_id`)',
                'KEY `u_id` (`u_id`)',
                'KEY `b_paytime` (`b_paytime`)',
            ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=\'账单表\'');
        }
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeDown()
     */
    public function safeDown() {
        $tableBill = Bill::tableName();
        if ($this->getDb()->getTableSchema($tableBill)) {
            $this->dropTable($tableBill);
        }
    }
}
