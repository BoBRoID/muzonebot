<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 6/21/17
 * Time: 11:55 PM
 */

namespace tg\bot\Base;

use common\models\User;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Telegram;

abstract class BaseSystemCommand extends SystemCommand
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

        if($this->botUser && !empty($this->botUser->language_id)){
            \Yii::$app->language = $this->botUser->language_id;
        }
    }


}