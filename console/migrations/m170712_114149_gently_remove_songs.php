<?php

use yii\db\Migration;

class m170712_114149_gently_remove_songs extends Migration
{
    public function safeUp()
    {
        $this->addColumn('songs', 'deleted', $this->boolean()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn('songs', 'deleted');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170712_114149_gently_remove_songs cannot be reverted.\n";

        return false;
    }
    */
}
