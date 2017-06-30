<?php

use yii\db\Migration;

/**
 * Handles the creation of table `bot_users`.
 */
class m170621_142253_create_bot_users_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('bot_users', [
            'id'            =>  $this->primaryKey()->unsigned(),
            'telegram_id'   =>  $this->integer()->notNull()->unsigned(),
            'created'       =>  $this->integer()->notNull()->unsigned(),
            'language_id'   =>  $this->integer()->notNull()->unsigned(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('bot_users');
    }
}
