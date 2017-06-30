<?php
namespace Longman\TelegramBot\Commands\SystemCommands;

use common\models\Song;
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
            $songs = Song::find()
                ->where(['like', 'title', $query])
                ->orWhere(['like', 'artist', $query]);
        }else{
            $songs = Song::find()->where('title IS NOT NULL')->andWhere('artist IS NOT NULL');
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
