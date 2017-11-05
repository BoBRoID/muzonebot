<?php

use yii\db\Migration;

/**
 * Handles the creation of table `pageviews`.
 */
class m171105_111516_create_pageviews_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('pageviews', [
            'id' => $this->primaryKey(),
            'action' => $this->string(64),
            'controlller' => $this->string(64),
            'userId' => $this->bigint()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('pageviews');
    }
}
