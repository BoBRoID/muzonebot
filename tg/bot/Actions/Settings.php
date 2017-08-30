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

    /**
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function run(): ServerResponse
    {
        $message = $this->update->getMessage();
        $chat_id = $message->getChat()->getId();

        $data = [
            'text'          =>  \Yii::t('general', 'Доступные настройки:'),
            'reply_markup'  =>  self::getMainKeyboard()
        ];

        if($this->update->getCallbackQuery()){
            \Yii::trace($data);
            return parent::run();
            return $this->updateCallbackQuery($data);
        }

        return parent::run();
        return Request::sendMessage($data + [
            'chat_id'       =>  $chat_id,
        ]);
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