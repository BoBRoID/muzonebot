<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class User
 * @package common\models
 * @property int    $id
 * @property string $first_name
 * @property string $last_name
 * @property string $username
 * @property string $created_at
 * @property string $updated_at
 * @property string $language_code
 * @property string $language_id
 * @property NotificationSettings[] $notificationSettings
 */
class User extends ActiveRecord
{

    /**
     * @var self[]
     */
    protected static $relatedUsers = [];

    /**
     * @var array
     */
    protected $notificationSettingsArray = [];

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'user';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['first_name', 'last_name', 'username', 'created_at', 'updated_at', 'language_code', 'language_id'], 'string']
        ];
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            [
                'class'                 =>  TimestampBehavior::class,
                'createdAtAttribute'    =>  'created_at',
                'updatedAtAttribute'    =>  'updated_at',
                'value'                 =>  time()
            ]
        ];
    }

    /**
     * @param string $telegramId
     * @return self|null
     */
    public static function findByTelegramId($telegramId)
    {
        foreach(self::$relatedUsers as $user){
            if($user->id == $telegramId){
                return $user;
            }
        }

        $relatedUser = self::findOne(['id' => $telegramId]);

        if($relatedUser !== null){
            self::$relatedUsers[] = $relatedUser;
        }

        return $relatedUser;
    }

    /**
     * @param Song $song
     * @return bool
     */
    public function addTrack(Song $song): bool
    {
        return (new UserSongs(['user_id' => $this->id, 'song_id' => $song->id]))->save();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotificationSettings()
    {
        return $this->hasMany(NotificationSettings::className(), ['user_id' => 'id']);
    }

    /**
     * @return array
     */
    public function getNotificationSettingsArray(): array
    {
        return array_merge($this->notificationSettings, $this->notificationSettingsArray);
    }

    /**
     * @param int $type
     * @return NotificationSettings
     */
    public function getNotificationSettingByType(int $type)
    {
        foreach($this->getNotificationSettingsArray() as $notificationSetting){
            if($notificationSetting->type === $type){
                return $notificationSetting;
            }
        }

        return null;
    }

    /**
     * @param int $type
     * @param bool $default
     * @return bool|int
     */
    public function getNotificationSettingValue(int $type, bool $default = false): bool
    {
        $notification = $this->getNotificationSettingByType($type);

        if(!$notification){
            return $default;
        }

        return (bool)$notification->value;
    }

    /**
     * @param $type
     * @param $value
     * @return bool
     */
    public function setNotificationSettingValue($type, $value): bool
    {
        $notification = $this->getNotificationSettingByType($type);

        if($notification === null){
            $notification = new NotificationSettings([
                'type'      =>  $type,
                'user_id'   =>  $this->id
            ]);
        }

        $notification->value = $value;

        if(!$notification->save()){
            return false;
        }

        $this->notificationSettingsArray[] = $notification;

        return true;
    }

}
