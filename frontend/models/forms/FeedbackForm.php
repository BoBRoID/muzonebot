<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 01.09.2017
 * Time: 12:22
 */

namespace frontend\models\forms;


use common\models\Feedback;
use yii\base\Model;

class FeedbackForm extends Model
{

    public $message;

    public $username;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['message', 'username'], 'string'],
            [['message'], 'required']
        ];
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        $feedback = new Feedback([
            'message'   =>  $this->message,
        ]);

        if(\Yii::$app->user->isGuest === false){
            $feedback->userId = \Yii::$app->user->id;
        }else if(!empty($this->username)){
            $feedback->username = $this->username;
        }

        if(!$feedback->save()){
            $this->addErrors($feedback->getErrors());
        }

        return true;
    }

    public function attributeLabels()
    {
        return [
            'message'   =>  \Yii::t('site', 'Текст отзыва'),
            'username'  =>  \Yii::t('site', 'username в телеграм')
        ];
    }

}