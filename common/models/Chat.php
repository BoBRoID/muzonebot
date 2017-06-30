<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "chat".
 *
 * @property integer $id
 * @property string $type
 * @property string $title
 * @property string $username
 * @property integer $all_members_are_administrators
 * @property string $created_at
 * @property string $updated_at
 * @property integer $old_id
 *
 * @property Conversation[] $conversations
 * @property EditedMessage[] $editedMessages
 * @property Message[] $messages
 * @property Message[] $messages0
 * @property UserChat[] $userChats
 * @property User[] $users
 */
class Chat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type'], 'required'],
            [['id', 'all_members_are_administrators', 'old_id'], 'integer'],
            [['type'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'username'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'title' => 'Title',
            'username' => 'Username',
            'all_members_are_administrators' => 'All Members Are Administrators',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'old_id' => 'Old ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversations()
    {
        return $this->hasMany(Conversation::className(), ['chat_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEditedMessages()
    {
        return $this->hasMany(EditedMessage::className(), ['chat_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['chat_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages0()
    {
        return $this->hasMany(Message::className(), ['forward_from_chat' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserChats()
    {
        return $this->hasMany(UserChat::className(), ['chat_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_chat', ['chat_id' => 'id']);
    }
}
