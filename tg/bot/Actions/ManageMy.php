<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 30.08.2017
 * Time: 11:41
 */

namespace tg\bot\Actions;


use app\bot\Entities\InlineKeyboardList;
use common\models\Song;
use common\models\UserSongs;
use Longman\TelegramBot\Entities\InlineKeyboardButton;

class ManageMy extends BaseAction
{

    const ACTION_ADD = 'add';
    const ACTION_REMOVE = 'rem';

    public function run(){
        $track = Song::findOne(['id' => $this->queryData->id]);

        if(!$track){
            return $this->answerCallbackQuery([
                'text'          =>  \Yii::t('general', 'Трек не найден!'),
                'show_alert'    =>  true
            ]);
        }

        $userSong = UserSongs::findOne(['song_id' => $track->id, 'user_id' => $this->botUser->id]);

        switch($this->queryData->a){
            case self::ACTION_ADD:
                if(!$userSong){
                    $userSong = new UserSongs([
                        'user_id'   =>  $this->botUser->id,
                        'song_id'   =>  $track->id
                    ]);

                    if(!$userSong->save()){
                        return $this->answerCallbackQuery([
                            'text'          =>  \Yii::t('general', 'Произошла ошибка при попытке добавить трек в мои!'),
                            'show_alert'    =>  true
                        ]);
                    }
                }
                break;
            case self::ACTION_REMOVE:
                if($userSong){
                    if(!$userSong->delete()){
                        return $this->answerCallbackQuery([
                            'text'          =>  \Yii::t('general', 'Произошла ошибка при попытке удалить трек из моих!'),
                            'show_alert'    =>  true
                        ]);
                    }

                    $userSong = null;
                }
                break;
        }

        if($userSong){
            $button = new InlineKeyboardButton([
                'text'          =>  \Yii::t('general', 'Удалить из моих'),
                'callback_data' =>  json_encode(['action' => 'manageMy', 'data' => ['a' => self::ACTION_REMOVE, 'id' => $track->id]])
            ]);
        }else{
            $button = new InlineKeyboardButton([
                'text'          =>  \Yii::t('general', 'Добавить в мои'),
                'callback_data' =>  json_encode(['action' => 'manageMy', 'data' => ['a' => self::ACTION_ADD, 'id' => $track->id]])
            ]);
        }

        return $this->updateCallbackQuery([
            'reply_markup'  =>  [
                new InlineKeyboardList([
                    $button
                ])
            ]
        ]);
    }


}