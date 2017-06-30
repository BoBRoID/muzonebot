<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 23.03.2017
 * Time: 13:02
 */

namespace tg\bot\Actions;

use common\models\Feedback;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;

class ViewReview extends BaseAction
{

    public function run(){
        $feedback = Feedback::findOne($this->queryData->id);

        $data = [
            'text'          =>  \Yii::t('general', 'Отзыв оставлен {date} пользователем @{username}{br} <code>{review}</code>', [
                'date'      =>  \Yii::$app->formatter->asDatetime($feedback->created),
                'username'  =>  htmlentities($feedback->username),
                'br'        =>  PHP_EOL,
                'review'    =>  $feedback->message
            ]),
            'parse_mode'    =>  'HTML',
        ];

        if(isset($this->queryData->type)){
            switch($this->queryData->type){
                case 'r':
                    $feedback->archived = 0;
                    break;
                case 'a':
                    $feedback->archived = 1;
                    break;
            }

            $feedback->save();
        }

        if(isset($this->queryData->from)){
            if($feedback->archived){
                $archiveButton = new InlineKeyboardButton([
                    'text'              =>  \Yii::t('general', 'Из архива'),
                    'callback_data'     =>  json_encode(['action' => 'viewReview', 'data' => ['id' => $this->queryData->id, 'type' => 'r', 'from' => $this->queryData->from]])
                ]);
            }else{
                $archiveButton = new InlineKeyboardButton([
                    'text'              =>  \Yii::t('general', 'В архив'),
                    'callback_data'     =>  json_encode(['action' => 'viewReview', 'data' => ['id' => $this->queryData->id, 'type' => 'a', 'from' => $this->queryData->from]])
                ]);
            }

            $data['reply_markup'] = new InlineKeyboard([
                new InlineKeyboardButton([
                    'text'              =>  \Yii::t('general', 'Назад'),
                    'callback_data'     =>  json_encode(['action' => 'reviews', 'data' => $this->queryData->from])
                ]),
                $archiveButton
            ]);
        }

        if(!$feedback->viewed){
            $feedback->viewed = 1;
            $feedback->save();
        }

        \Yii::trace($data);

        return $this->updateCallbackQuery($data);
    }

}