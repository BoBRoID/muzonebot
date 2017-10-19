<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 17.10.17
 * Time: 12:46
 */

namespace tg\models;


use common\models\Language;
use Longman\TelegramBot\Commands\Command;

class User extends \common\models\User
{

    /**
     * @param Command $command
     * @return self
     */
    public static function initializeBotUser(Command $command)
    {
        if(self::$_botUser === null){
            $update = $command->getUpdate();

            $entity = $update->getUpdateContent();
            $from = null;

            try{
                $from = $entity->getFrom();
            }catch (\Exception $e){
                return null;
            }

            \Yii::trace($from);

            if($from === null){
                return null;
            }

            $botUserData = [];

            try{
                $userID = $from->getId();
                $botUserData['id']  =  $userID;
            }catch (\Exception $e){
                return null;
            }

            $language_code = null;

            try{
                $language_code = $from->getLanguageCode();
            }catch (\Exception $e){};

            $botUser = self::findByTelegramId($userID);

            \Yii::trace('$botUser === null ? '.(string)$botUser === null);

            if($botUser === null){
                try{
                    $botUserData['first_name'] = $from->getFirstName();
                }catch (\Exception $e){}

                try{
                    $botUserData['last_name'] = $from->getLastName();
                }catch (\Exception $e){}

                try{
                    $botUserData['username'] = $from->getUsername();
                }catch (\Exception $e){}

                if($language_code !== null){
                    $botUserData['language_code'] = $language_code;
                }

                \Yii::trace($botUserData);

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


}