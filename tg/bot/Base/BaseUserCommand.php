<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 21.06.2017
 * Time: 17:19
 */

namespace tg\bot\Base;

use tg\models\User;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Telegram;

abstract class BaseUserCommand extends UserCommand
{

    public $botUser = false;

    /**
     * UserCommand constructor.
     * @param Telegram $telegram
     * @param Update $update
     */
    public function __construct(Telegram $telegram, Update $update)
    {
        parent::__construct($telegram, $update);

        if($this->botUser === false){
            $this->botUser = User::initializeBotUser($this);
        }

        if($this->botUser !== null && !empty($this->botUser->language_id)){
            \Yii::$app->language = $this->botUser->language_id;
        }
    }

}