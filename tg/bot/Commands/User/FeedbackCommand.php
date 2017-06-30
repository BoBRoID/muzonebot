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

use tg\bot\Base\BaseUserCommand;
use common\models\Feedback;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

/**
 * User "/help" command
 */
class FeedbackCommand extends BaseUserCommand
{
    /**
     * @var string
     */
    protected $name = 'feedback';

    /**
     * @var string
     */
    protected $description = 'You can leave feedback for bot dev team';

    /**
     * @var string
     */
    protected $usage = '/feedback <your wishes>';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    public function __construct(Telegram $telegram, Update $update = null)
    {
        $this->description = \Yii::t('general', 'Оставить отзыв разработчику');
        $this->usage = \Yii::t('general', '/feedback <отзыв>');

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
        $feedback    = trim($message->getText(true));

        $data = [
            'chat_id'             => $chat_id,
            'reply_to_message_id' => $message_id,
        ];

        if(empty($feedback)){
            return Request::sendMessage($data + [
                'parse_mode'    => 'Markdown',
                'text'      =>  \Yii::t('general', 'Вы не ввели текст отзыва! Чтобы оставить отзыв, напишите его после команды, чтобы получилось `/feedback <отзыв>`'),
            ]);
        }

        $userFeedback = new Feedback([
            'userId'    =>  $message->getFrom()->getId(),
            'username'  =>  $message->getFrom()->getUsername(),
            'message'   =>  $feedback,
            'created'   =>  time()
        ]);

        $message = \Yii::t('general', 'Произошла ошибка, при попытке добавить отзыв. Нам очень жаль :(');

        if($userFeedback->save()){
            $message = \Yii::t('general', 'Спасибо за оставленный отзыв!');

            foreach(\Yii::$app->params['adminsId'] as $adminId){
                if($adminId == $userFeedback->userId){
                    continue;
                }

                Request::sendMessage([
                    'chat_id'   =>  $adminId,
                    'text'      =>  \Yii::t('general', 'Пользователь @{username} оставил отзыв о боте!', [
                        'username'  =>  $userFeedback->username
                    ]),
                    'reply_markup'  =>  new InlineKeyboard([
                        new InlineKeyboardButton([
                            'text'              =>  \Yii::t('general', 'Просмотреть'),
                            'callback_data'     =>  json_encode(['action' => 'viewReview', 'data' => ['id' => $userFeedback->id]])
                        ])
                    ])
                ]);
            }
        }

        return Request::sendMessage($data + [
            'text'  => $message,
        ]);
    }
}
