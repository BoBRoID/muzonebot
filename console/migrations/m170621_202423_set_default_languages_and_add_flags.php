<?php

use yii\db\Migration;

class m170621_202423_set_default_languages_and_add_flags extends Migration
{

    protected $flags = [
        'ru-RU' =>  '\ud83c\uddf7\ud83c\uddfa',
        'en-US' =>  '\ud83c\uddfa\ud83c\uddf8',
        'uk-UA' =>  '\ud83c\uddfa\ud83c\udde6'
    ];

    public function safeUp()
    {
        $this->update('{{%language}}', ['status' => 0]);
        $this->update('{{%language}}', ['status' => 1], ['in', 'language_id', ['ru-RU', 'uk-UA', 'en-US']]);

        foreach($this->flags as $language_id => $flag){
            $this->update('{{%language}}', ['flag' => $flag], ['language_id' => $language_id]);
        }
    }

    public function safeDown()
    {
        echo "m170621_202423_set_default_languages_and_add_flags cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170621_202423_set_default_languages_and_add_flags cannot be reverted.\n";

        return false;
    }
    */
}
