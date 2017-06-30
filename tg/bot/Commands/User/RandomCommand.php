<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Written by Marco Boretto <marco.bore@gmail.com>
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use tg\bot\Base\BaseUserCommand;
use common\models\Song;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use yii\db\Expression;

/**
 * User "/random" command
 */
class RandomCommand extends BaseUserCommand
{
    /**
     * @var string
     */
    protected $name = 'random';

    /**
     * @var string
     */
    protected $description = 'Provides to you random song';

    /**
     * @var string
     */
    protected $usage = '/random';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    public function __construct(Telegram $telegram, Update $update = null)
    {
        $this->description = \Yii::t('general', 'Отправить случайную песню из библиотеки');

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

        /**
         * @var $song Song
         */
        $song = Song::find()->orderBy(new Expression('RAND()'))->one();

        Request::sendAudio([
            'chat_id'               =>  $message->getChat()->getId(),
            'audio'                 =>  $song->fileId,
            'title'                 =>  $song->title,
            'performer'             =>  $song->artist,
            'reply_to_message_id'   =>  $message->getMessageId()
        ]);
    }
}
