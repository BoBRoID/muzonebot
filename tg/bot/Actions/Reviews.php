<?php
namespace tg\bot\Actions;

use common\models\Feedback;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Request;
use yii\db\Expression;

/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 23.03.2017
 * Time: 12:43
 */
class Reviews extends BaseAction
{

    public function run(){
        $query = Feedback::find();
        $type = null;

        switch($this->queryData){
            case 'all':
                $type = \Yii::t('general', 'Все');
                break;
            case 'new':
                $type = \Yii::t('general', 'Новые');
                $query->where(['viewed' => 0, 'archived' => 0]);
                break;
            case 'old':
                $type = \Yii::t('general', 'Прочитаные');
                $query->where(['viewed' => 1, 'archived' => 0]);
                break;
            case 'archived':
                $type = \Yii::t('general', 'Архивные');
                $query->where(['archived' => 1]);
                break;
            case 'index':
                return $this->runIndex();
                break;
            default:
                return $this->answerCallbackQuery('wrong param received');
                break;
        }

        $count = $query->count();
        $text = \Yii::t('general', '{type} отзывы отсутствуют', ['type' => $type]);

        if($count){
            $text = \Yii::t('general', '{type} отзывы {count}', [
                'type'  =>  $type,
                'count' =>  $count ? '(всего '.$count.')' : null
            ]);
        }

        return $this->updateCallbackQuery([
            'text'          =>  $text,
            'reply_markup'  =>  $this->generateReplyMarkup($query->all()),
        ]);
    }

    /**
     * @param $rows Feedback[]
     * @return InlineKeyboard|object
     */
    protected function generateReplyMarkup($rows){
        $keyboardButtons = [];

        foreach($rows as $row){
            $keyboardButtons[] = new InlineKeyboardButton([
                'text'              =>  $row->username,
                'callback_data'     =>  json_encode(['action' => 'viewReview', 'data' => ['id' => $row->id, 'from' => $this->queryData]])
            ]);
        }

        $keyboardButtons[] = new InlineKeyboardButton([
            'text'              =>  \Yii::t('general', 'Назад'),
            'callback_data'     =>  json_encode(['action' => 'reviews', 'data' => 'index'])
        ]);

        $reflect = new \ReflectionClass(InlineKeyboard::class);
        $keyboard = $reflect->newInstanceArgs($keyboardButtons);

        return $keyboard;
    }

    public function runIndex(){
        $data['text'] = \Yii::t('general', 'К сожалению, на данный момент отзывы отсутствуют');

        $reviews = Feedback::find()->select([
            'total'     =>  new Expression('COUNT(*)'),
            'viewed'    =>  new Expression('SUM(`viewed`)'),
            'archived'  =>  new Expression('SUM(`archived`)')
        ])->asArray()->one();

        $reviews['new'] = $reviews['total'] - $reviews['viewed'];

        if($reviews['total'] != 0){
            $data['text'] = \Yii::t('general', 'Отзывы пользователей о боте: ``` Всего отзывов: {total}`````` Новых: {new} ``` ', [
                'total' =>  $reviews['total'],
                'new'   =>  $reviews['new'],
                'br'    =>  PHP_EOL
            ]);

            $data['reply_markup'] = new InlineKeyboard([
                new InlineKeyboardButton([
                    'text'              =>  \Yii::t('general', 'Новые {count}', ['count' => ($reviews['new'] ? '(+'.$reviews['new'].')' : null)]),
                    'callback_data'     =>  json_encode(['action' => 'reviews', 'data' => 'new'])
                ]),
            ],[
                new InlineKeyboardButton([
                    'text'              =>  \Yii::t('general', 'Прочитаные'),
                    'callback_data'     =>  json_encode(['action' => 'reviews', 'data' => 'old'])
                ]),
            ],[
                new InlineKeyboardButton([
                    'text'              =>  \Yii::t('general', 'Все'),
                    'callback_data'     =>  json_encode(['action' => 'reviews', 'data' => 'all'])
                ])
            ],[
                new InlineKeyboardButton([
                    'text'              =>  \Yii::t('general', 'Архивные {count}', ['count' => $reviews['archived'] ? '('.$reviews['archived'].')' : null]),
                    'callback_data'     =>  json_encode(['action' => 'reviews', 'data' => 'archived'])
                ]),
            ]);

            $data['parse_mode'] = 'Markdown';
        }

        if($this->update->getCallbackQuery()){
            return $this->updateCallbackQuery($data);
        }

        return Request::sendMessage($data + ['chat_id' => $this->update->getMessage()->getChat()->getId()]);
    }

}