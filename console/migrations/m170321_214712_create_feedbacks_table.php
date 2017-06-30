<?php

use yii\db\Migration;

/**
 * Handles the creation of table `feedbacks`.
 */
class m170321_214712_create_feedbacks_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('feedbacks', [
            'id'        =>  $this->primaryKey()->unsigned(),
            'created'   =>  $this->integer()->unsigned()->notNull(),
            'userId'    =>  $this->integer(),
            'username'  =>  $this->text(),
            'message'   =>  $this->text(),
            'viewed'    =>  $this->integer(1)->unsigned()->notNull()->defaultValue(0)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('feedbacks');
    }
}
