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

use common\models\Chat;
use common\models\Song;
use Longman\TelegramBot\Commands\AdminCommand;
use Longman\TelegramBot\Request;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * Admin "/stats" command
 */
class StatsCommand extends AdminCommand
{
    /**
     * @var string
     */
    protected $name = 'stats';

    /**
     * @var string
     */
    protected $description = 'Stats command for view some stats';

    /**
     * @var string
     */
    protected $usage = '/stats';

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

        $data = ['chat_id' => $chat->getId()];

        $debug_info = [];

        $songsCount = Song::find()->count();

        $chatsStats = Chat::find()
            ->select(['type', 'count' => new Expression('COUNT(`type`)')])
            ->groupBy('type')
            ->asArray()
            ->all();

        $chatsCount = array_sum(ArrayHelper::getColumn($chatsStats, 'count'));

        array_walk($chatsStats, function(&$item){
            switch($item['type']){
                case 'channel':
                    $item['type'] = \Yii::t('chats_types', 'Каналов');
                    break;
                case 'private':
                    $item['type'] = \Yii::t('chats_types', 'Пользователей');
                    break;
                case 'group':
                    $item['type'] = \Yii::t('chats_types', 'Групп');
                    break;
                case 'supergroup':
                    $item['type'] = \Yii::t('chats_types', 'Супергрупп');
                    break;
            }
        });

        /**
         * @var $lastSong Song
         */
        $lastSong = Song::find()->orderBy('id DESC')->one();
        $debug_info[] = \Yii::t('general', 'Статистика по диалогам: ');
        $debug_info[] = \Yii::t('general', '``` Всего диалогов: {chatsCount} ```', ['chatsCount' => $chatsCount]);
        foreach($chatsStats as $chatsStat){
            $debug_info[] = "``` \t{$chatsStat['type']}: {$chatsStat['count']} ```";
        }
        $debug_info[] = ' ';
        $debug_info[] = \Yii::t('general', 'Статистика по трекам:');
        $debug_info[] = \Yii::t('general', '``` Всего треков: {songsCount} ```', ['songsCount' => $songsCount]);
        $debug_info[] = ' ';
        $debug_info[] = \Yii::t('general', ' Последний трек: ');
        $debug_info[] = \Yii::t('general', '``` Исполнитель: {artist}', ['artist' => $lastSong->artist]);
        $debug_info[] = \Yii::t('general', ' Название: {title} ', ['title' => $lastSong->title]);
        $debug_info[] = \Yii::t('general', ' Добавлена: {added} ```', ['added' => \Yii::$app->formatter->asDatetime($lastSong->added)]);

        $data['parse_mode'] = 'Markdown';
        $data['text'] = implode(PHP_EOL, $debug_info);

        return Request::sendMessage($data);
    }
}
