<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 17.10.17
 * Time: 12:46
 */

namespace tg\models;


use common\models\Language;
use Longman\TelegramBot\Entities\ChannelPost;
use Longman\TelegramBot\Entities\EditedChannelPost;
use Longman\TelegramBot\Entities\Update;

class User extends \common\models\User
{

    /**
     * @var self[]
     */
    protected static $_botUser = [];

    /**
     * @param Update $update
     * @return self
     */
    public static function initializeBotUser(Update $update)
    {
        $entity = $update->getUpdateContent();

        $language_code = null;

        if($entity instanceof ChannelPost || $entity instanceof EditedChannelPost){
            $tgUserData = self::getUserDataFromChannelPost($entity);
        }else{
            $from = $entity->getFrom();

            /**
             * @var $from \Longman\TelegramBot\Entities\User
             */

            if($from === null || $from instanceof \Longman\TelegramBot\Entities\User === false){
                return null;
            }

            $tgUserData = self::getUserDataFromUserEntity($from);
        }

        if(array_key_exists($tgUserData->getId(), self::$_botUser) === false) {
            $botUser = $tgUserData->getId() !== null ? self::findByTelegramId($tgUserData->getId()) : null;

            if ($botUser === null) {
                $botUser = new self([
                    'id' => $tgUserData->getId(),
                    'first_name' => $tgUserData->getFirstName(),
                    'last_name' => $tgUserData->getLastName(),
                    'username' => $tgUserData->getUsername(),
                    'language_code' => $tgUserData->getLanguageCode()
                ]);
            }

            if ($tgUserData->getLanguageCode() !== null && empty($botUser->language_code)) {
                $language = Language::findOne(['language' => $tgUserData->getLanguageCode(), 'status' => 1]);

                if (!$language) {
                    $language = Language::findOne(['default' => 1]);
                }

                if ($language) {
                    $botUser->language_id = $language->language_id;
                }
            }

            $botUser->save(false);

            self::$_botUser[$tgUserData->getId()] = $botUser;
        }

        return self::$_botUser[$tgUserData->getId()];
    }

    /**
     * @param $channelPost ChannelPost|EditedChannelPost
     * @return TgUserData
     */
    protected static function getUserDataFromChannelPost($channelPost){
        return new TgUserData([
            'id'        =>  $channelPost->getChat()->getId(),
            'firstName' =>  $channelPost->getChat()->getTitle()
        ]);
    }

    /**
     * @param $userEntity \Longman\TelegramBot\Entities\User
     * @return TgUserData
     */
    protected static function getUserDataFromUserEntity($userEntity){
        return new TgUserData([
            'id'            =>  $userEntity->getId(),
            'firstName'     =>  $userEntity->getFirstName(),
            'lastName'      =>  $userEntity->getLastName(),
            'username'      =>  $userEntity->getUsername(),
            'languageCode'  =>  $userEntity->getLanguageCode()
        ]);
    }


}