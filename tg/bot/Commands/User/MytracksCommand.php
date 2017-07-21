<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 21.07.2017
 * Time: 13:49
 */

namespace Longman\TelegramBot\Commands\UserCommands;


use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use tg\bot\Base\BaseUserCommand;

class MytracksCommand extends BaseUserCommand
{

    /**
     * @var string
     */
    protected $name = 'mytracks';

    /**
     * @var string
     */
    protected $description = 'Provides to you description of my tracks';

    /**
     * @var string
     */
    protected $usage = '/mytracks';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    public function __construct(Telegram $telegram, Update $update = null)
    {
        $this->description = \Yii::t('general', 'Информация о разделе "Мои треки"');

        parent::__construct($telegram, $update);
    }

    /**
     * Command execute method
     *
     * @return mixed
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();

        return Request::sendMessage([
            'chat_id'               =>  $message->getChat()->getId(),
            'text'                  =>  \Yii::t('general', 'Все треки отправленные вами бот добавляет в раздел "мои треки". Чтобы получить доступ к этому разделу, достаточно написать `@muzonebot`, или `@muzonebot {keyword}`. Добавить трек в данный раздел можно отправив трек в диалог к боту, или там где есть бот. Чтобы удалить трек из этого раздела, отправьте его в приватный диалог с ботом.', ['keyword' => \Yii::t('general', 'мои треки')]),
            'reply_to_message_id'   =>  $message->getMessageId()
        ]);
    }
    
}