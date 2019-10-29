<?php


namespace common\models;


use Curl\Curl;
use yii\base\Model;
use yii\helpers\Inflector;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class TelegramFile extends Model
{

    public  $fileId,
            $fileSize,
            $filePath;

    /**
     * @param string $fileId
     * @param int $timeout
     * @return static
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public static function getFreshByFileId(string $fileId, int $timeout = 5): self
    {
        $apiKey = \Yii::$app->params['apiKey'];

        $curl = new Curl();
        $curl->setOpt(CURLOPT_TIMEOUT, $timeout);
        $curl->post("https://api.telegram.org/bot{$apiKey}/getFile", ['file_id' => $fileId]);

        $response = json_decode($curl->response, true);

        if ((bool)$response['ok'] !== true) {
            $errorMessage = $response['description'];

            switch ($response['error_code']) {
                case 400:
                    throw new BadRequestHttpException($errorMessage);
                default:
                    throw new NotFoundHttpException($errorMessage);
            }
        }

        $data = [];

        foreach ($response['result'] as $key => $value) {
            $data[Inflector::variablize($key)] = $value;
        }

        return new self($data);
    }

    /**
     * @param string $fileId
     * @return static|null
     */
    public static function getByFileId(string $fileId): ?self
    {
        return \Yii::$app->cache->getOrSet(['telegramFile', 'fileId' => $fileId], function () use ($fileId) {
           return self::getFreshByFileId($fileId);
        }, 3000);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        $apiKey = \Yii::$app->params['apiKey'];

        return "https://api.telegram.org/file/bot{$apiKey}/{$this->filePath}";
    }

    /**
     * @return bool
     */
    public function checkIsAvailable(): bool
    {
        $headers = get_headers($this->getUrl(), 1);

        return stripos($headers[0], '200 OK') !== false;
    }

}