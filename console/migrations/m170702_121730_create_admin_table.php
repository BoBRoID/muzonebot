<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin`.
 */
class m170702_121730_create_admin_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('admin', [
            'id'        =>  $this->bigInteger(20)->unsigned()->notNull(),
            'added'     =>  $this->integer()->unsigned(),
            'added_by'  =>  $this->bigInteger(20)->unsigned()
        ]);

        $this->addPrimaryKey('id', 'admin', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('admin');
    }
}
