<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_songs".
 *
 * @property integer $user_id
 * @property integer $song_id
 *
 * @property Song $song
 * @property User $user
 */
class UserSongs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_songs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'song_id'], 'required'],
            [['user_id', 'song_id'], 'integer'],
            [['user_id', 'song_id'], 'unique', 'targetAttribute' => ['user_id', 'song_id'], 'message' => 'The combination of User ID and Song ID has already been taken.'],
            [['song_id'], 'exist', 'skipOnError' => true, 'targetClass' => Song::className(), 'targetAttribute' => ['song_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'song_id' => 'Song ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSong()
    {
        return $this->hasOne(Song::className(), ['id' => 'song_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
