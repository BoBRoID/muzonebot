<?php

use yii\db\Migration;

/**
 * Handles adding archived to table `feedbacks`.
 */
class m170523_081810_add_archived_column_to_feedbacks_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('feedbacks', 'archived', $this->boolean()->unsigned()->notNull()->defaultValue(0));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('feedbacks', 'archived');
    }
}
