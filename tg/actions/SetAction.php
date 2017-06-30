<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 03.01.2017
 * Time: 11:22
 */

namespace tg\actions;


use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;
use yii\base\Action;
use yii\helpers\Url;

class SetAction extends Action
{

    public function run(){
        try{
            $telegram = new Telegram(\Yii::$app->params['apiKey'], \Yii::$app->params['botName']);
            $telegram->deleteWebhook();
            $result = $telegram->setWebhook(Url::to(['/site/hook'], true), [
                'max_connections'   =>  '10'
            ]);

            if($result->isOk()){
                echo $result->getDescription();
            }
        }catch (TelegramException $e){
            echo $e;
        }
    }

}