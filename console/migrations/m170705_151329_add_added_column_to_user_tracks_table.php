<?php

use yii\db\Migration;

/**
 * Handles adding added to table `user_tracks`.
 */
class m170705_151329_add_added_column_to_user_tracks_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('user_songs', 'added', $this->integer()->unsigned());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('user_songs', 'added');
    }
}
