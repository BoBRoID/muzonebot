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
        $message = $command->getMessage();
        $userID = $botUser = $language_code = null;

        \Yii::trace($message);

        if($message){
            $userID = $message->getFrom()->getId();
            $language_code = $message->getFrom()->getLanguageCode();
        }else{
            $data = json_decode($command->getTelegram()->getCustomInput());

            if(isset($data->callback_query)){
                $userID = $data->callback_query->from->id;
            }else if(isset($data->inline_query)){
                $userID = $data->inline_query->from->id;
            }
        }

        $botUser = self::findByTelegramId($userID);

        if(is_null($botUser)){
            $botUser = new User([
                'id'            =>  $message->getFrom()->getId(),
                'first_name'    =>  $message->getFrom()->getFirstName(),
                'last_name'     =>  $message->getFrom()->getLastName(),
                'username'      =>  $message->getFrom()->getUsername(),
                'language_code' =>  $message->getFrom()->getLanguageCode(),
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

}
