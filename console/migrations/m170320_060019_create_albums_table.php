<?php

use yii\db\Migration;

/**
 * Handles the creation of table `albums`.
 */
class m170320_060019_create_albums_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('albums', [
            'id'        =>  $this->primaryKey()->unsigned(),
            'name'      =>  $this->text(),
            'year'      =>  $this->integer(4),
            'artistID'  =>  $this->integer()->unsigned()->notNull(),
            'tracks'    =>  $this->integer()->notNull()->defaultValue(0),
            'cover'     =>  $this->text(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('albums');
    }
}
