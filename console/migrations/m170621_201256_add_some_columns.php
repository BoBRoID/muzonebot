<?php

use yii\db\Migration;

class m170621_201256_add_some_columns extends Migration
{
    public function safeUp()
    {
        $this->addColumn('songs', 'user_id', $this->integer()->unsigned()->notNull());
        $this->addColumn('{{%language}}','flag', $this->text());

        $this->addForeignKey('fk-songs-user_id-bot_users-id', 'songs', 'user_id', 'bot_users', 'id');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-songs-user_id-bot_users-id', 'songs');

        $this->dropColumn('songs', 'user_id');
        $this->dropColumn('{{%language}}', 'flag');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170621_201256_add_some_columns cannot be reverted.\n";

        return false;
    }
    */
}
