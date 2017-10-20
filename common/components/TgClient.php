<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 20.10.17
 * Time: 18:40
 */

namespace common\components;


use Longman\TelegramBot\Telegram;

class TgClient
{

    private static $_tg;

    /**
     * @return Telegram
     */
    private static function getTg(): Telegram
    {
        if(self::$_tg === null){
            self::$_tg = new Telegram(\Yii::$app->params['apiKey'], \Yii::$app->params['botName']);
        }

        return self::$_tg;
    }

    /**
     * @param $message
     * @param $chatId
     * @param array $params
     * @return \Longman\TelegramBot\Entities\ServerResponse
     */
    public static function sendMessage(string $message, int $chatId, array $params = []){
        self::getTg();

        $params = array_merge($params, [
            'text'      =>  $message,
            'chat_id'   =>  $chatId
        ]);

        return \Longman\TelegramBot\Request::sendMessage($params);
    }

    /**
     * @param string $fileId
     * @param int $chatId
     * @param string $message
     * @param array $params
     * @return \Longman\TelegramBot\Entities\ServerResponse
     */
    public static function sendFile(string $fileId, int $chatId, string $message = null, array $params = []){
        self::getTg();

        $params = array_merge($params, [
            'audio'     =>  $fileId,
            'chat_id'   =>  $chatId,
            'caption'   =>  $message
        ]);

        return \Longman\TelegramBot\Request::sendAudio($params);

    }

    /**
     * @param $fileId
     * @return \Longman\TelegramBot\Entities\ServerResponse
     */
    public static function getFile(string $fileId){
        self::getTg();

        return \Longman\TelegramBot\Request::getFile(['file_id' => $fileId]);

    }

}