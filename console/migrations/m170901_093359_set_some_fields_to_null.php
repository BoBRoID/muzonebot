<?php

use yii\db\Migration;

class m170901_093359_set_some_fields_to_null extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('feedbacks', 'userId', $this->integer()->null());
        $this->alterColumn('feedbacks', 'username', $this->integer()->null());
    }

    public function safeDown()
    {
        $this->alterColumn('feedbacks', 'userId', $this->integer()->notNull());
        $this->alterColumn('feedbacks', 'username', $this->integer()->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170901_093359_set_some_fields_to_null cannot be reverted.\n";

        return false;
    }
    */
}
