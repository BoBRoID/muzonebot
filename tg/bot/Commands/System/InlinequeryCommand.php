<?php
namespace Longman\TelegramBot\Commands\SystemCommands;

use common\models\Song;
use common\models\UserSongs;
use tg\bot\Base\BaseSystemCommand;
use Longman\TelegramBot\Entities\InlineQuery\InlineQueryResultArticle;
use Longman\TelegramBot\Entities\InlineQuery\InlineQueryResultCachedAudio;
use Longman\TelegramBot\Entities\InputMessageContent\InputTextMessageContent;
use Longman\TelegramBot\Request;
use yii\data\ActiveDataProvider;

/**
 * Inline query command
 */
class InlinequeryCommand extends BaseSystemCommand
{
    /**
     * @var string
     */
    protected $name = 'inlinequery';

    /**
     * @var string
     */
    protected $description = 'Reply to inline query';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * Command execute method
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute(){
        $update       = $this->getUpdate();
        $inline_query = $update->getInlineQuery();
        $query        = $inline_query->getQuery();

        $data    = ['inline_query_id' => $inline_query->getId()];
        $results = $articles = [];

        $songs = Song::find();

        if ($query !== '') {
            $myTracksText = \Yii::t('general', 'мои треки');
            $myTracks = preg_match('/^'.$myTracksText.'/', $query);

            if($myTracks){
                $query = trim(mb_substr($query, strlen($myTracksText) + 1));
            }

            if($query){
                $songs->andWhere(['or', [['like', 'songs.title', $query], ['like', 'songs.artist', $query]]]);
            }
        }else{
            $myTracks = true;
        }

        if($myTracks){
            $songs->leftJoin(['us' => UserSongs::tableName()], 'us.song_id = songs.id')
                ->andWhere(['us.user_id' => $this->botUser->id])
                ->orderBy('us.added DESC');
        }

        $songsProvider = new ActiveDataProvider([
            'query'         =>  $songs,
            'pagination'    =>  [
                'pageSize'  =>  49
            ],
        ]);

        if($inline_query->getOffset()){
            $songsProvider->pagination->setPage($inline_query->getOffset());
        }

        if(!empty($songsProvider->getModels())){
            /**
             * @var $song Song
             */
            foreach($songsProvider->getModels() as $song){
                $results[] = new InlineQueryResultCachedAudio([
                    'id'            =>  '0'.$song->id,
                    'audio_file_id' =>  $song->fileId
                ]);
            }

            if($songsProvider->getPagination()->getPageCount() > $songsProvider->getPagination()->getPage() + 1){
                $data['next_offset'] = $songsProvider->getPagination()->getPage() + 1;
            }
        }else{
            if(!empty($query)){
                $description = \Yii::t('general', 'К сожалению, по запросу `{query}` треки не найдены. {br}Хотите добавить? Отправьте их боту!', [
                    'query' =>  $query,
                    'br'    =>  PHP_EOL
                ]);
                $command = '/help@MuzOneBot';
            }else{
                $description = \Yii::t('general', 'Здесь будут отображаться ваши треки, когда вы их добавите. Чтобы найти трек, продолжайте вводить имя исполнителя или название трека. Хотите узнать как добавить себе треков? Нажмите на сообщение!', [
                    'query' =>  $query,
                    'br'    =>  PHP_EOL
                ]);
                $command = '/mytracks@MuzOneBot';
            }

            $results[] = new InlineQueryResultArticle([
                'id'    =>  '001',
                'title' =>  \Yii::t('general', 'Нет результатов'),
                'description'   =>  $description,
                'input_message_content' =>  new InputTextMessageContent([
                    'message_text'      => $command])
            ]);
        }

        $data['results'] = '[' . implode(',', $results) . ']';

        return Request::answerInlineQuery($data);
    }
}
