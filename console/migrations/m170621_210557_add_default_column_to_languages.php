<?php

use yii\db\Migration;

class m170621_210557_add_default_column_to_languages extends Migration
{
    public function safeUp()
    {
        $this->addColumn('language', 'default', $this->boolean()->unsigned()->notNull()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn('language', 'default');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170621_210557_add_default_column_to_languages cannot be reverted.\n";

        return false;
    }
    */
}
