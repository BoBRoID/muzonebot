<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 03.01.2017
 * Time: 11:19
 */

namespace tg\actions;


use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\TelegramLog;
use yii\base\Action;

class WebHookAction extends Action
{

    public $view;

    public function run(){
        try{
            $telegram = new Telegram(\Yii::$app->params['apiKey'], \Yii::$app->params['botName']);

            TelegramLog::initUpdateLog(\Yii::getAlias('@logs').'/update.log');
            TelegramLog::initDebugLog(\Yii::getAlias('@logs').'/debug.log');
            TelegramLog::initErrorLog(\Yii::getAlias('@logs').'/error.log');

            $telegram->enableExternalMySql(\Yii::$app->db->masterPdo);
            //$telegram->enableBotan(\Yii::$app->params['botanKey']);

            $telegram->addCommandsPath(\Yii::getAlias('@app').'/bot/Commands');
            $telegram->enableAdmins(\Yii::$app->params['adminsId']);
            $telegram->handle();
        }catch (TelegramException $e){
            \Yii::debug($e->getMessage());
        }
    }

}