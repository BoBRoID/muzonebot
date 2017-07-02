<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin_tokens`.
 */
class m170702_114131_create_admin_tokens_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('admin_token', [
            'user_id'   =>  $this->bigInteger()->notNull(),
            'token'     =>  $this->string(64)->unique(),
            'expire'    =>  $this->integer()->unsigned()->notNull(),
            'verified'  =>  $this->boolean()->unsigned()->defaultValue(0)
        ]);

        $this->addPrimaryKey('tokenPK', 'admin_token', 'token');

        $this->addForeignKey('fk-admin_token-user_id-user_id', 'admin_token', 'user_id', 'user', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('admin_token');
    }
}
