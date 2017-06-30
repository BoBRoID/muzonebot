<?php

use yii\db\Migration;

class m170626_160829_change_structure_of_some_tables extends Migration
{
    public function safeUp()
    {
        $this->dropForeignKey('fk-songs-user_id-bot_users-id', 'songs');
        $this->execute('UPDATE `songs`, `bot_users` SET `songs`.`user_id` = `bot_users`.`telegram_id` WHERE `songs`.`user_id` = `bot_users`.`id`');
        $this->addColumn('user', 'language_id', $this->string(5)->null());
        $this->execute('UPDATE `bot_users`, `user` SET `user`.`language_id` = `bot_users`.`language_id` WHERE `user`.`id` = `bot_users`.`telegram_id`');
    }

    public function safeDown()
    {
        echo "m170626_160829_change_structure_of_some_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170626_160829_change_structure_of_some_tables cannot be reverted.\n";

        return false;
    }
    */
}
