<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 29.06.2017
 * Time: 18:05
 */

namespace common\helpers;


use GuzzleHttp\Exception\BadResponseException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class TrackDownloader
{

    private static $fileIdCacheDuration = (50 * 60);

    /**
     * @param $telegramFileId string
     * @return bool|string
     * @throws \yii\web\BadRequestHttpException
     * @throws \Longman\TelegramBot\Exception\TelegramException
     * @throws NotFoundHttpException
     */
    public static function get($telegramFileId){
        $opts = [
            'https'=>[
                'method'    => 'GET',
                'header'    =>  "Accept-language: en\r\n".
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
     * @param int $timeout
     * @return string
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function getUrl(string $telegramFileId, int $timeout = 30): string
    {
        new Telegram(\Yii::$app->params['apiKey'], \Yii::$app->params['botName']);

        $filePath = \Yii::$app->cache->getOrSet('tg:'.$telegramFileId, function() use ($telegramFileId){
            $fileRequest = Request::getFile(['file_id' => $telegramFileId]);

            if($fileRequest->getOk() === false){
                if((int)$fileRequest->getErrorCode() === 400){
                    throw new BadRequestHttpException($fileRequest->getResult());
                }

                throw new NotFoundHttpException();
            }

            return $fileRequest->getResult()->getFilePath();
        }, self::$fileIdCacheDuration);

        return 'https://api.telegram.org/file/bot'.\Yii::$app->params['apiKey'].'/'.preg_replace("/ /", "%20", $filePath);
    }

}