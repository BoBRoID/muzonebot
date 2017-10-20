<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 20.10.17
 * Time: 18:48
 */

namespace backend\models;


use common\components\TgClient;
use yii\base\Model;

class FileToChatForm extends Model
{

    public $fileId;

    public $chatId;

    public $caption;

    public function rules()
    {
        return [
            [['fileId', 'caption'], 'string'],
            [['chatId'], 'integer']
        ];
    }

    /**
     * @return \Longman\TelegramBot\Entities\ServerResponse
     */
    public function send(){
        return TgClient::sendFile($this->fileId, $this->chatId, $this->caption);
    }

}