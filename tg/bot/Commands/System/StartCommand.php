<?php
namespace Longman\TelegramBot\Commands\SystemCommands;

use common\models\Admin;
use common\models\AdminToken;
use tg\bot\Base\BaseSystemCommand;
use common\models\UserToken;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

/**
 * Start command
 */
class StartCommand extends BaseSystemCommand
{
    /**
     * @var string
     */
    protected $name = 'start';

    /**
     * @var string
     */
    protected $description = 'Start command';

    /**
     * @var string
     */
    protected $usage = '/start';

    /**
     * @var string
     */
    protected $version = '1.1.0';


    /**
     * ChangelanguageCommand constructor.
     * @param Telegram $telegram
     * @param Update|null $update
     */
    public function __construct(Telegram $telegram, Update $update = null)
    {
        $this->description = \Yii::t('general', 'Информация о боте');

        parent::__construct($telegram, $update);
    }

    /**
     * Command execute method
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute(){
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        $authKey = trim($message->getText(true));

        if($authKey && strlen($authKey) == 64){
            $userToken = new UserToken(['token' => $authKey, 'user_id' => $this->botUser->id]);

            if($userToken->save()){
                $text = \Yii::t('general', 'Вы успешно авторизовались на сайте бота!');
            }else{
                \Yii::error($userToken->getErrors());
                $text = \Yii::t('general', 'Произошла ошибка при попытке авторизовать вас на сайте бота!');
            }
        }else if($authKey && strpos('admin', $authKey) === 0 && strlen($authKey) == 69 && (in_array($this->botUser->id, \Yii::$app->params['adminsId']) || Admin::findOne(['id' => $this->botUser->id]))){
            $adminToken = new AdminToken(['token' => substr($authKey, 5, 64), 'user_id' => $this->botUser->id]);

            if($adminToken->save()){
                $text = \Yii::t('general', 'Вы успешно авторизовались в админке бота!');
            }else{
                \Yii::error($adminToken->getErrors());
                $text = \Yii::t('manage', 'Произошла ошибка при попытке авторизовать вас в админке бота!');
            }
        }else{
            $text = \Yii::t('manage', 'Для смены языка используйте команду /changelanguage. {br}{br}Приветствую! Я - МузОн бот. Меня создали, чтобы предоставлять удобный интерфейс для поиска треков в телеграмм. Поиск происходит по моей собственной базе (добавлять треки в которую проще простого), и доступен из любого диалога. Если хочешь найти трек, воспользуйся inline режимом работы бота. Для этого в любом диалоге (даже здесь) достаточно написать `@MuzOneBot <название трека или исполнитель>` (например, `@MuzOneBot Snoop Dogg`). Не нашёл свой трек? Отправь его мне, и я немедленно добавлю его себе! Кидаешь друзьям музыку в диалоге? Добавь меня в диалог - я вам не помешаю, а вся музыка из диалога будет доступна в поиске. Нужна помощь? Напиши /help, и просмотри список доступных команд. Есть пожелания, предложения, или что-то работает не так? Оставь отзыв при помощи команды `/feedback <отзыв>`. Спасибо что заглянули :)', ['br' => PHP_EOL]);
        }

        $data = [
            'chat_id'   =>  $chat_id,
            'text'      =>  $text,
            'parse_mode'=>  'Markdown'
        ];

        return Request::sendMessage($data);
    }
}
