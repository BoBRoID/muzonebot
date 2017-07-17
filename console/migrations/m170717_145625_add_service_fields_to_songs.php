<?php

use yii\db\Migration;

class m170717_145625_add_service_fields_to_songs extends Migration
{
    public function safeUp()
    {
        $this->addColumn('songs', 'isBig', $this->boolean()->unsigned()->notNull()->defaultValue(0));
        $this->addColumn('songs', 'last_update', $this->integer()->unsigned()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn('songs', 'isBig');
        $this->dropColumn('songs', 'last_update');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170717_145625_add_service_fields_to_songs cannot be reverted.\n";

        return false;
    }
    */
}
