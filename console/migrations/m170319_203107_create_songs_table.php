<?php

use yii\db\Migration;

/**
 * Handles the creation of table `songs`.
 */
class m170319_203107_create_songs_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('songs', [
            'id'        =>  $this->primaryKey()->unsigned(),
            'fileId'    =>  $this->string(40)->notNull()->unique(),
            'title'     =>  $this->text(),
            'artist'    =>  $this->text(),
            'duration'  =>  $this->integer()->unsigned()->notNull(),
            'added'     =>  $this->integer()->unsigned()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('songs');
    }
}
