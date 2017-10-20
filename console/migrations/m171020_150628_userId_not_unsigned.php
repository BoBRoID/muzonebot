<?php

use yii\db\Migration;

class m171020_150628_userId_not_unsigned extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('songs', 'user_id', $this->bigInteger()->notNull());
    }

    public function safeDown()
    {
        $this->alterColumn('songs', 'user_id', $this->bigInteger()->notNull()->unsigned());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171020_150628_userId_not_unsigned cannot be reverted.\n";

        return false;
    }
    */
}
