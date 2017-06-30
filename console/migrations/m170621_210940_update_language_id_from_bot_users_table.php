<?php

use yii\db\Migration;

class m170621_210940_update_language_id_from_bot_users_table extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('bot_users', 'language_id', $this->string(5)->null());
    }

    public function safeDown()
    {
        $this->alterColumn('bot_users', 'language_id', $this->integer()->notNull()->unsigned());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170621_210940_update_language_id_from_bot_users_table cannot be reverted.\n";

        return false;
    }
    */
}
