<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 30.08.2017
 * Time: 13:35
 */

namespace tg\bot\Actions;


use app\bot\Entities\InlineKeyboardList;
use common\helpers\Emoji;
use common\models\NotificationSettings;
use common\models\User;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Request;

class ManageNotifications extends BaseAction
{

    public function run(){
        if(isset($this->queryData->t)){

        }

        return $this->runIndex();
    }

    public function runIndex(){
        $data = [
            'text'          =>  'Настройки уведомлений',
            'reply_markup'  =>  $this->getMainKeyboard()
        ];

        if($this->update->getCallbackQuery()){
            return $this->updateCallbackQuery($data);
        }

        return Request::sendMessage($data + [
            'chat_id'   =>  $this->update->getMessage()->getChat()->getId()
        ]);
    }

    /**
     * @return InlineKeyboardList
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function getMainKeyboard(): InlineKeyboardList
    {
        $buttons = [];

        foreach(NotificationSettings::getTypesDescriptions() as $type => $description){
            $buttons[] = new InlineKeyboardButton([
                'text'  =>  \Yii::t('general', '{reason} {state}', [
                    'reason'        =>  $description,
                    'state'         =>  $this->botUser->getNotificationSettingValue($type) ? Emoji::render(Emoji::BELL) : Emoji::render(Emoji::BELL_CANCEL)
                ]),
                'callback_data' =>  json_encode(['action' => 'manageNotifications', 'data' => ['t' => $type]])
            ]);
        }

        $buttons[] = new InlineKeyboardButton([
            'text'          =>  \Yii::t('general', 'Назад'),
            'callback_data' =>  json_encode(['action' => 'settings'])
        ]);

        return new InlineKeyboardList($buttons);
    }

}