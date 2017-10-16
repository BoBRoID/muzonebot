<?php

namespace common\models;

use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\ChannelPost;
use Longman\TelegramBot\Entities\Entity;
use Longman\TelegramBot\Entities\MessageEntity;
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
     * @var self
     */
    protected static $_botUser = null;

    protected $notificationSettingsArray = [];

    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['first_name', 'last_name', 'username', 'created_at', 'updated_at', 'language_code', 'language_id'], 'string']
        ];
    }

    public function behaviors()
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
     * @param Command $command
     * @return self
     */
    public static function initializeBotUser(Command $command){
        if(self::$_botUser === null){
            $update = $command->getUpdate();

            $entity = $update->getMessage()
                ?? $update->getCallbackQuery()
                ?? $update->getInlineQuery()
                ?? $update->getChannelPost()
                ?? $update->getEditedMessage()
                ?? $update->getChosenInlineResult()
                ?? null;

            \Yii::trace($entity);

            if($entity === null){
                return null;
            }

            \Yii::trace($entity->toJson());

            $botUserData = [];

            try{
                $userID = $entity->getFrom()->getId();
                $botUserData['id']  =  $userID;
            }catch (\Exception $e){
                return null;
            }

            $language_code = null;

            try{
                $language_code = $entity->getFrom()->getLanguageCode();
            }catch (\Exception $e){};

            $botUser = self::findByTelegramId($userID);

            if($botUser === null){
                try{
                    $botUserData['first_name'] = $entity->getFrom()->getFirstName();
                }catch (\Exception $e){}

                try{
                    $botUserData['last_name'] = $entity->getFrom()->getLastName();
                }catch (\Exception $e){}

                try{
                    $botUserData['username'] = $entity->getFrom()->getUsername();
                }catch (\Exception $e){}

                if($language_code !== null){
                    $botUserData['language_code'] = $language_code;
                }

                $botUser = new self($botUserData);
            }

            if($language_code !== null && empty($botUser->language_code)){
                $language = Language::findOne(['language' => $language_code, 'status' => 1]);

                if(!$language){
                    $language = Language::findOne(['default' => 1]);
                }

                if($language){
                    $botUser->language_id = $language->language_id;
                }

                $botUser->save(false);
            }

            self::$_botUser = $botUser;
        }

        return self::$_botUser;
    }

    /**
     * @param string $telegramId
     * @return self|null
     */
    public static function findByTelegramId($telegramId){
        foreach(self::$relatedUsers as $user){
            if($user->id == $telegramId){
                return $user;
            }
        }

        $relatedUser = self::findOne(['id' => $telegramId]);

        if(!is_null($relatedUser)){
            self::$relatedUsers[] = $relatedUser;
        }

        return $relatedUser;
    }

    /**
     * @param Song $song
     * @return bool
     */
    public function addTrack(Song $song){
        return (new UserSongs(['user_id' => $this->id, 'song_id' => $song->id]))->save();
    }

    public function getNotificationSettings(){
        return $this->hasMany(NotificationSettings::className(), ['user_id' => 'id']);
    }

    /**
     * @return array
     */
    public function getNotificationSettingsArray(): array{
        return array_merge($this->notificationSettings, $this->notificationSettingsArray);
    }

    /**
     * @param int $type
     * @return NotificationSettings
     */
    public function getNotificationSettingByType(int $type){
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
    public function getNotificationSettingValue(int $type, bool $default = false): bool{
        $notification = $this->getNotificationSettingByType($type);

        if(!$notification){
            return $default;
        }

        return (bool)$notification->value;
    }

    public function setNotificationSettingValue($type, $value){
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
