<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 23.03.2017
 * Time: 12:50
 */

namespace tg\bot\Actions;

use common\models\User;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use yii\base\Exception;

class BaseAction
{

    /**
     * @var Command|null
     */

    protected $command = null;

    /**
     * @var null|Update
     */
    protected $update = null;

    /**
     * @var null|\stdClass
     */
    protected $queryData = null;

    /**
     * @var null|User
     */
    protected $botUser = null;

    /**
     * BaseAction constructor.
     * @param $command Command
     * @param $queryData \stdClass
     * @throws Exception
     */
    public function __construct($command, $queryData = null)
    {
        if(empty($command) || $command instanceof Command == false){
            throw new Exception('wrong use of action! $command must be an instance of Longman\TelegramBot\Commands\Command!');
        }

        $this->command = $command;
        $this->update = $command->getUpdate();

        if($this->update->getCallbackQuery() && $userId = $this->update->getCallbackQuery()->getFrom()->getId()){
            $this->botUser = User::findByTelegramId($userId);

            \Yii::$app->language = $this->botUser->language_id;
        }

        if(!is_null($queryData)){
            if($queryData instanceof \stdClass == false){
                $queryData = new \stdClass($queryData);
            }

            if(isset($queryData->data)){
                $this->queryData = $queryData->data;
            }
        }
    }

    public function run(){
        return $this->answerCallbackQuery('do nothing');
    }

    protected function answerCallbackQuery($data){
        if(!is_array($data)){
            $data = ['text' => $data];
        }

        $callback_query    = $this->update->getCallbackQuery();
        $callback_query_id = $callback_query->getId();

        return Request::answerCallbackQuery(array_merge([
            'callback_query_id' => $callback_query_id,
            'cache_time'        => 5,
        ], $data));
    }

    protected function updateCallbackQuery($data){
        if(!is_array($data)){
            $data = ['text' => $data];
        }

        $callback_query    = $this->update->getCallbackQuery();

        return Request::editMessageText(array_merge([
            'chat_id'   =>  $callback_query->getMessage()->getChat()->getId(),
            'message_id'=>  $callback_query->getMessage()->getMessageId(),
        ], $data));
    }

    /**
     * @param User $botUser
     * @return $this
     */
    public function setBotUser(User $botUser){
        $this->botUser = $botUser;

        return $this;
    }
}