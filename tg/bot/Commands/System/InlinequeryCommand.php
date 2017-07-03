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

        if ($query !== '') {
            $myTracks = mb_strpos(\Yii::t('general', 'мои треки'), $query) === 0;

            if($myTracks){
                $query = mb_substr($query, 10);
            }

            $songs = Song::find()
                ->where(['like', 'songs.title', $query])
                ->orWhere(['like', 'songs.artist', $query]);

            if($myTracks){
                $songs->leftJoin(['us' => UserSongs::tableName()], 'us.song_id = songs.id')
                    ->andWhere(['us.user_id' => $this->botUser->id]);
            }
        }else{
            $songs = Song::find()->where('songs.title IS NOT NULL')->andWhere('songs.artist IS NOT NULL');
        }

        $songsProvider = new ActiveDataProvider([
            'query'         =>  $songs,
            'pagination'    =>  [
                'pageSize'  =>  50
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
                    'id'            =>  '00'.$song->id,
                    'audio_file_id' =>  $song->fileId
                ]);
            }

            if($songsProvider->getPagination()->getPageCount() > $songsProvider->getPagination()->getPage() + 1){
                $data['next_offset'] = $songsProvider->getPagination()->getPage() + 1;
            }
        }else{
            $results[] = new InlineQueryResultArticle([
                'id'    =>  '001',
                'title' =>  \Yii::t('general', 'Нет результатов'),
                'description'   =>  \Yii::t('general', 'К сожалению, по запросу `{query}` треки не найдены. {br}Хотите добавить? Отправьте их боту!', [
                    'query' =>  $query,
                    'br'    =>  PHP_EOL
                ]),
                'input_message_content' =>  new InputTextMessageContent([
                    'message_text' => '/help@MuzOneBot'])
            ]);
        }

        $data['results'] = '[' . implode(',', $results) . ']';

        return Request::answerInlineQuery($data);
    }
}
