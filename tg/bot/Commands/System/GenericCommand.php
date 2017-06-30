<?php
namespace Longman\TelegramBot\Commands\SystemCommands;

use tg\bot\Base\BaseSystemCommand;
use Longman\TelegramBot\Request;

/**
 * Generic command
 */
class GenericCommand extends BaseSystemCommand
{
    /**
     * @var string
     */
    protected $name = 'Generic';

    /**
     * @var string
     */
    protected $description = 'Handles generic commands or is executed by default when a command is not found';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * Command execute method
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();

        //You can use $command as param
        $chat_id = $message->getChat()->getId();
        $user_id = $message->getFrom()->getId();
        $command = $message->getCommand();

        //If the user is and admin and the command is in the format "/whoisXYZ", call the /whois command
        if (stripos($command, 'whois') === 0 && $this->telegram->isAdmin($user_id)) {
            return $this->telegram->executeCommand('whois');
        }

        if($this->getMessage()->getChat()->isPrivateChat() == false && preg_match('/@'.$message->getBotName().'/i', $message->getFullCommand()) == false){
            return Request::emptyResponse();
        }

        $data = [
            'chat_id' => $chat_id,
            'text'    => \Yii::t('general', 'Команда /{command} не найдена :(', ['command' => $command]),
        ];

        return Request::sendMessage($data);
    }
}
