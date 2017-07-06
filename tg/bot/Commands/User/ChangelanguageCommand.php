<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 05.01.2017
 * Time: 13:12
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use tg\bot\Actions\ChangeLanguage;
use tg\bot\Base\BaseUserCommand;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

class ChangelanguageCommand extends BaseUserCommand
{

    protected $name = 'changelanguage';
    protected $description = 'Смена языка';
    protected $usage = '/changelanguage';
    protected $version = '1.0.0';

    /**
     * ChangelanguageCommand constructor.
     * @param Telegram $telegram
     * @param Update|null $update
     */
    public function __construct(Telegram $telegram, Update $update = null)
    {
        $this->description = \Yii::t('general', 'Смена языка');

        parent::__construct($telegram, $update);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(){
        return (new ChangeLanguage($this))->setBotUser($this->botUser)->runIndex();
    }
}