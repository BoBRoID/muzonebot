<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use tg\bot\Base\BaseUserCommand;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use yii\helpers\Url;

/**
 * User "/help" command
 */
class WebsiteCommand extends BaseUserCommand
{
    /**
     * @var string
     */
    protected $name = 'website';

    /**
     * @var string
     */
    protected $description = 'Show bots website';

    /**
     * @var string
     */
    protected $usage = '/website';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    public function __construct(Telegram $telegram, Update $update = null)
    {
        $this->description = \Yii::t('general', 'Веб-сайт бота');

        parent::__construct($telegram, $update);
    }

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

        $message_id = $message->getMessageId();

        $text = \Yii::t('general', 'Посетите веб-сайт бота для того чтобы просмотреть список треков, список ваших треков, а также сможете добавить несколько себе.');

        $data = [
            'chat_id'               =>  $chat_id,
            'reply_to_message_id'   =>  $message_id,
            'text'                  =>  $text,
            'reply_markup'          =>  new InlineKeyboard([
                new InlineKeyboardButton([
                    'text'  =>  \Yii::t('general', 'Перейти'),
                    'url'   =>  Url::to([\Yii::$app->params['frontend_url'], 'language' => \Yii::$app->language])
                ])
            ])
        ];

        return Request::sendMessage($data);
    }
}
