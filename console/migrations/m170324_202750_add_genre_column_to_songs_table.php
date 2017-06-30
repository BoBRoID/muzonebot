<?php

use yii\db\Migration;

/**
 * Handles adding genre to table `songs`.
 */
class m170324_202750_add_genre_column_to_songs_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('songs', 'genreID', $this->integer()->unsigned()->notNull()->defaultValue(255));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('songs', 'genreID');
    }
}
