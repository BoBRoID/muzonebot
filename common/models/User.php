<?php

namespace common\models;

use Longman\TelegramBot\Commands\Command;
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

    /**
     * @param Command $command
     * @return self
     */
    public static function initializeBotUser(Command $command){
        $entity = null;
        $update = $command->getUpdate();

        if($update->getMessage()){
            $entity = $update->getMessage();
        }else if($update->getCallbackQuery()){
            $entity = $update->getCallbackQuery();
        }else if($update->getInlineQuery()){
            $entity = $update->getInlineQuery();
        }else if($update->getEditedMessage()){
            $entity = $update->getEditedMessage();
        }else if($update->getChannelPost()){
            $update->getChannelPost();
        }else if($update->getChosenInlineResult()){
            $update->getChosenInlineResult();
        }

        if($entity){
            $userID = $entity->getFrom()->getId();
            $language_code = $entity->getFrom()->getLanguageCode();
        }else{
            return null;
        }

        $botUser = self::findByTelegramId($userID);

        if(is_null($botUser)){
            $botUser = new User([
                'id'            =>  $entity->getFrom()->getId(),
                'first_name'    =>  $entity->getFrom()->getFirstName(),
                'last_name'     =>  $entity->getFrom()->getLastName(),
                'username'      =>  $entity->getFrom()->getUsername(),
                'language_code' =>  $entity->getFrom()->getLanguageCode(),
                'created_at'    =>  time(),
                'updated_at'    =>  time()
            ]);
        }

        if(!is_null($language_code) && empty($botUser->language_code)){
            $language = Language::findOne(['language' => $language_code, 'status' => 1]);

            if(!$language){
                $language = Language::findOne(['default' => 1]);
            }

            if($language){
                $botUser->language_id = $language->language_id;
            }

            $botUser->save(false);
        }

        return $botUser;
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
     * @param $type
     * @param bool $default
     * @return bool|int
     */
    public function getNotificationSettingValue($type, $default = false){
        foreach($this->notificationSettings as $notificationSetting){
            if($notificationSetting->type === $type){
                return $notificationSetting->value;
            }
        }

        return $default;
    }

    public function setNotificationSettingValue($type, $value){
        $notification = new NotificationSettings([
            'type'      =>  $type,
            'value'     =>  $value,
            'user_id'   =>  $this->id
        ]);

        foreach($this->notificationSettings as $notificationSetting){
            if($notificationSetting->type === $type){
                $notification = $notificationSetting;
                break;
            }
        }

        if(!$notification->save()){
            return false;
        }

        $this->notificationSettings[] = $notification;

        return true;
    }

}
