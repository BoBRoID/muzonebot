<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_token`.
 */
class m170622_083031_create_user_token_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user_token', [
            'user_id'   =>  $this->bigInteger()->notNull(),
            'token'     =>  $this->string(64)->unique(),
            'expire'    =>  $this->integer()->unsigned()->notNull()
        ]);

        $this->addPrimaryKey('tokenPK', 'user_token', 'token');

        $this->addForeignKey('fk-user_token-user_id-user_id', 'user_token', 'user_id', 'user', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-user_token-user_id-user_id', 'user_token');

        $this->dropTable('user_token');
    }
}
