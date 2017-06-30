<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\AdminCommands;

use tg\bot\Actions\Reviews;
use Longman\TelegramBot\Commands\AdminCommand;
use Longman\TelegramBot\Request;

/**
 * Admin "/stats" command
 */
class FeedbacksCommand extends AdminCommand
{
    /**
     * @var string
     */
    protected $name = 'feedbacks';

    /**
     * @var string
     */
    protected $description = 'Возвращает пользовательские отзывы';

    /**
     * @var string
     */
    protected $usage = '/feedbacks';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Command execute method
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat = $message->getChat();
        $text = strtolower($message->getText(true));

        $data = ['chat_id' => $chat->getId()];

        if ($text !== 'glasnost' && !$chat->isPrivateChat()) {
            $data['text'] = \Yii::t('general', 'Данный функционал доступен только в приватном диалоге с ботом!');

            return Request::sendMessage($data);
        }

        $reviewsAction = new Reviews($this);
        return $reviewsAction->runIndex();

    }
}
