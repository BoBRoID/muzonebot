<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 21.06.2017
 * Time: 17:19
 */

namespace tg\bot\Base;

use common\models\User;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Telegram;

abstract class BaseUserCommand extends UserCommand
{

    public $botUser = null;

    /**
     * UserCommand constructor.
     * @param Telegram $telegram
     * @param Update $update
     */
    public function __construct(Telegram $telegram, Update $update)
    {
        parent::__construct($telegram, $update);

        if(!$this->botUser){
            $this->botUser = User::initializeBotUser($this);
        }

        if($this->botUser && !empty($this->botUser->language_code)){
            \Yii::$app->language = $this->botUser->language_code;
        }
    }

}