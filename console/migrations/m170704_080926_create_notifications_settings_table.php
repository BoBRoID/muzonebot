<?php

use yii\db\Migration;

/**
 * Handles the creation of table `notifications_settings`.
 */
class m170704_080926_create_notifications_settings_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('notifications_settings', [
            'user_id'   =>  $this->bigInteger(20)->notNull(),
            'type'      =>  $this->integer()->unsigned()->notNull(),
            'value'     =>  $this->boolean()
        ]);

        $this->addPrimaryKey('settings', 'notifications_settings', ['user_id', 'type']);
        $this->addForeignKey('fk-notifications_settings-user_id-user-id', 'notifications_settings', 'user_id', 'user', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-notifications_settings-user_id-user-id', 'notifications_settings');
        $this->dropTable('notifications_settings');
    }
}
