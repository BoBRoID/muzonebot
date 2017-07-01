<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 22.06.2017
 * Time: 18:31
 */

namespace tg\bot\Actions;

use app\bot\Entities\InlineKeyboardList;
use common\models\Language;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Request;

class ChangeLanguage extends BaseAction
{

    public function run(){
        if($this->queryData){
            $language = Language::findOne(['status' => 1, 'language_id' => $this->queryData]);

            if($language && $this->botUser){
                $this->botUser->language_id = $this->queryData;

                if($this->botUser->save(false)){
                    \Yii::$app->language = $this->queryData;
                }
            }
        }

        return $this->updateCallbackQuery([
            'chat_id'       =>  $this->update->getCallbackQuery()->getMessage()->getChat()->getId(),
            'text'          =>  \Yii::t('general', 'В данный момент доступны следующие языки:'),
            'reply_markup'  =>  $this->getReplyMarkup()
        ]);
    }

    public function runIndex(){
        return Request::sendMessage([
            'chat_id'       =>  $this->update->getMessage()->getChat()->getId(),
            'text'          =>  \Yii::t('general', 'В данный момент доступны следующие языки:'),
            'reply_markup'  =>  $this->getReplyMarkup()
        ]);
    }

    /**
     * @return InlineKeyboardList
     */
    public function getReplyMarkup(){
        $buttons = [];

        foreach(Language::findAll(['status' => 1]) as $language){
            $buttons[] = new InlineKeyboardButton([
                'text'          =>  $language->name.($language->language_id == $this->botUser->language_id ? ' ('.\Yii::t('general', 'текущий').') ' : '').($language->flag ? json_decode('"'.$language->flag.'"') : null),
                'callback_data' =>  json_encode(['action' => 'changeLanguage', 'data' => $language->language_id,])
            ]);
        }

        return new InlineKeyboardList($buttons);
    }


}