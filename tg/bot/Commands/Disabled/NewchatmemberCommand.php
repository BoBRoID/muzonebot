<?php
namespace Longman\TelegramBot\Commands\SystemCommands;

use tg\bot\Base\BaseSystemCommand;
use Longman\TelegramBot\Request;

/**
 * New chat member command
 */
class NewchatmemberCommand extends BaseSystemCommand
{
    /**
     * @var string
     */
    protected $name = 'Newchatmember';

    /**
     * @var string
     */
    protected $description = 'New Chat Member';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    protected $enabled = false;

    /**
     * Command execute method
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();
        $member  = $message->getNewChatMember();
        $text    = 'Hi there!';

        if (!$message->botAddedInChat()) {
            $text = 'Hi ' . $member->tryMention() . '!';
        }

        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
        ];

        return Request::sendMessage($data);
    }
}
