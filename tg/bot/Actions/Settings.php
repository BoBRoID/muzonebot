<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 30.08.2017
 * Time: 13:38
 */

namespace tg\bot\Actions;


use app\bot\Entities\InlineKeyboardList;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class Settings extends BaseAction{

    public function run()
    {
        return $this->runIndex();
    }

    /**
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function runIndex(): ServerResponse
    {
        $message = $this->update->getMessage();
        $chat_id = $message->getChat()->getId();

        $data = [
            'chat_id'       =>  $chat_id,
            'text'          =>  \Yii::t('general', 'Доступные настройки:'),
            'reply_markup'  =>  self::getMainKeyboard()
        ];

        if($this->update->getCallbackQuery()){
            $this->updateCallbackQuery($data);
        }

        return Request::sendMessage($data);
    }

    /**
     * @return InlineKeyboardList
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function getMainKeyboard(): InlineKeyboardList
    {
        return new InlineKeyboardList([
            new InlineKeyboardButton([
                'text'          =>  \Yii::t('general', 'Настройка оповещений'),
                'callback_data' =>  json_encode(['action' => 'manageNotifications'])
            ])
        ]);
    }

}