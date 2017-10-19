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

            \Yii::trace($update->getUpdateContent());
            \Yii::trace($update->getUpdateType());

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


}