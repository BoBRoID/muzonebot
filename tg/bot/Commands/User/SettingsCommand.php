<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 30.08.2017
 * Time: 13:06
 */

namespace Longman\TelegramBot\Commands\UserCommands;


use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Telegram;
use tg\bot\Actions\Settings;
use tg\bot\Base\BaseUserCommand;

class SettingsCommand extends BaseUserCommand
{

    /**
     * @var string
     */
    protected $name = 'settings';

    /**
     * @var string
     */
    protected $description = 'You can set settings for bot';

    /**
     * @var string
     */
    protected $usage = '/settings';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    public function __construct(Telegram $telegram, Update $update)
    {
        $this->description = \Yii::t('general', 'Настройте бота под себя');

        parent::__construct($telegram, $update);
    }

    /**
     * Command execute method
     *
     * @return mixed
     * @throws \yii\base\Exception
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute(){
        $settingsAction = new Settings($this);
        return $settingsAction->runIndex();
    }

}