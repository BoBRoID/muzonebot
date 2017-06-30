<?php

use yii\db\Migration;

class m170629_154503_add_columns_to_songs_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('songs', 'bitrate', $this->integer()->unsigned()->defaultValue(0));
        $this->addColumn('songs', 'format', $this->text());
        $this->execute('UPDATE `songs` SET `format` = \'mp3\'');
    }

    public function safeDown()
    {
        $this->dropColumn('songs', 'bitrate');
        $this->dropColumn('songs', 'format');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170629_154503_add_columns_to_songs_table cannot be reverted.\n";

        return false;
    }
    */
}
