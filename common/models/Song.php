<?php

namespace common\models;

use Longman\TelegramBot\Entities\Audio;

/**
 * This is the model class for table "songs".
 *
 * @property int $id
 * @property string $fileId
 * @property string $title
 * @property string $artist
 * @property int $duration
 * @property int $genreID
 * @property int $added
 * @property int $user_id
 * @property int $deleted
 *
 * @property User $user
 * @property UserSongs $userSong
 */
class Song extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'songs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fileId', 'title'], 'required'],
            [['title', 'artist'], 'string'],
            [['duration', 'added', 'genreID', 'user_id', 'deleted'], 'integer'],
            [['fileId'], 'string', 'max' => 40],
            [['fileId'], 'unique'],
        ];
    }

    public function beforeSave($insert)
    {
        if($this->isNewRecord){
            $this->added = time();
        }

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * @param $audio Audio
     */
    public function loadAudio($audio){
        $this->setAttributes([
            'title'     =>  $audio->getTitle(),
            'artist'    =>  $audio->getPerformer(),
            'duration'  =>  $audio->getDuration(),
            'fileId'    =>  $audio->getFileId(),
        ], false);
    }

    /**
     * @param $songTags SongTags
     */
    public function loadTags($songTags){
        foreach($songTags->toArray() as $var => $value){
            if(!isset($this->$var) || empty($value)){
                continue;
            }

            $this->$var = $value;
        }

        if(is_int($songTags->genre)){
            $this->genreID = $songTags->genre;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getUserSong(){
        return $this->hasOne(UserSongs::className(), ['song_id' => 'id'])->andWhere(['user_songs.user_id' => \Yii::$app->user->isGuest ? null : \Yii::$app->user->id]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fileId' => 'File ID',
            'title' => 'Title',
            'artist' => 'Artist',
            'duration' => 'Duration',
            'added' => 'Added',
            'genreID' => 'genreID',
            'user_id'   =>  'userID',
            'deleted'   =>  'deleted',
        ];
    }
}
