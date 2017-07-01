<?php

use yii\db\Migration;

/**
 * Handles the creation of table `genres`.
 */
class m170324_202801_create_genres_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('genres', [
            'id'    =>  $this->integer()->unsigned()->notNull(),
            'name'  =>  $this->string(50)->notNull()
        ]);

        $this->addPrimaryKey('name-id', 'genres', ['id', 'name']);
        //$this->addForeignKey('fk-songs-genreID-genres-id', 'songs', 'genreID', 'genres', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('genres');
    }
}
