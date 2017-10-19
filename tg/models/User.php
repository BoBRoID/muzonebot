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
     * @var self
     */
    protected static $_botUser = false;

    /**
     * @param Command $command
     * @return self
     */
    public static function initializeBotUser(Command $command)
    {
        if(self::$_botUser === false){
            $update = $command->getUpdate();

            $entity = $update->getUpdateContent();
            $from = null;

            \Yii::trace('getting from');

            try{
                $from = $entity->getFrom();
            }catch (\Exception $e){
                return null;
            }

            \Yii::trace($from);

            /**
             * @var $from \Longman\TelegramBot\Entities\User
             */

            if($from === null || $from instanceof \Longman\TelegramBot\Entities\User){
                return null;
            }

            $userID = $from->getId();
            $language_code = $from->getLanguageCode();

            $botUser = self::findByTelegramId($userID);

            if($botUser === null){
                $botUser = new self([
                    'id'            =>  $userID,
                    'first_name'    =>  $from->getFirstName(),
                    'last_name'     =>  $from->getLastName(),
                    'username'      =>  $from->getUsername(),
                    'language_code' =>  $language_code
                ]);
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

        \Yii::trace(self::$_botUser);

        return self::$_botUser;
    }


}