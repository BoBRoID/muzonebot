<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_songs`.
 */
class m170629_091031_create_user_songs_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user_songs', [
            'user_id'   =>  $this->bigInteger(20)->notNull(),
            'song_id'   =>  $this->integer()->notNull()->unsigned()
        ]);

        $this->createIndex('user_song', 'user_songs', ['user_id', 'song_id'], true);

        $this->addForeignKey('fk-user_songs-song_id-songs-id', 'user_songs', 'song_id', 'songs', 'id');
        $this->addForeignKey('fk-user_songs-user_id-user-id', 'user_songs', 'user_id', 'user', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-user_songs-song_id-songs-id', 'user_songs');
        $this->dropForeignKey('fk-user_songs-user_id-user-id', 'user_songs');
        $this->dropIndex('user_song', 'user_songs');
        $this->dropTable('user_songs');
    }
}
