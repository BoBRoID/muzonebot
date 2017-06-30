<?php

use yii\db\Migration;

/**
 * Handles the creation of table `songs`.
 */
class m170319_203106_longman extends Migration
{
    /**
     * @inheritdoc
     */
    public function up(){
        $this->execute(file_get_contents(\Yii::getAlias('@vendor').'/longman/telegram-bot/structure.sql'));
    }

    /**
     * @inheritdoc
     */
    public function down(){
        echo 'This migration can\'t be reverted!';

        return false;
    }
}
