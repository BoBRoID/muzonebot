<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 29.06.2017
 * Time: 18:05
 */

namespace common\helpers;


use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use yii\web\NotFoundHttpException;

class TrackDownloader
{

    /**
     * @param $telegramFileId string
     * @return bool|string
     * @throws NotFoundHttpException
     */
    public static function get($telegramFileId){
        $opts = [
            'https'=>[
                'method'=>"GET",
                'header'=>"Accept-language: en\r\n" .
                    "Cookie: foo=bar\r\n"
            ],
            'ssl' =>[
                'verify_peer' => false,
            ]
        ];


        return file_get_contents(self::getUrl($telegramFileId), false, stream_context_create($opts));
    }

    /**
     * @param $telegramFileId string
     * @return string
     * @throws NotFoundHttpException
     */
    public static function getUrl($telegramFileId){
        new Telegram(\Yii::$app->params['apiKey'], \Yii::$app->params['botName']);
        $fileRequest = Request::getFile(['file_id' => $telegramFileId]);

        \Yii::trace($fileRequest);

        if($fileRequest->getOk() == false){
            throw new NotFoundHttpException();
        }

        return 'https://api.telegram.org/file/bot'.\Yii::$app->params['apiKey'].'/'.preg_replace("/ /", "%20", $fileRequest->getResult()->getFilePath());
    }

}