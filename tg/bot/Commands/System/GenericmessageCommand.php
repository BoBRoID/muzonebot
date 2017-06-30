<?php


namespace Longman\TelegramBot\Commands\SystemCommands;

use tg\bot\Base\BaseSystemCommand;
use common\helpers\TagExtractor;
use common\helpers\TrackDownloader;
use common\models\Song;
use Longman\TelegramBot\Request;
use yii\base\ErrorException;

/**
 * Generic message command
 */
class GenericmessageCommand extends BaseSystemCommand
{
    /**
     * @var string
     */
    protected $name = 'Genericmessage';

    /**
     * @var string
     */
    protected $description = 'Handle generic message';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * @var bool
     */
    protected $need_mysql = true;

    /**
     * Execution if MySQL is required but not available
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     */
    public function execute(){
        $message = $this->getMessage();

        $data = [
            'chat_id' => $message->getChat()->getId()
        ];

        if($audio = $message->getAudio()){
            if($song = Song::findOne(['fileId' => $audio->getFileId()])){
                if($message->getChat()->isPrivateChat()){
                    return Request::sendMessage([
                        'text'                  => \Yii::t('general', 'Данный трек уже есть в нашей базе!'),
                        'reply_to_message_id'   =>  $message->getMessageId()
                    ] + $data);
                }else{
                    return Request::emptyResponse();
                }
            }

            Request::sendMessage([
                'text' => \Yii::t('general', 'Получен трек{track}. Пробую его проиндексировать...', [
                    'track'     =>  $audio->getPerformer() && $audio->getTitle() ? \Yii::t('general', ' "{artist} - {title}"', [
                        'artist'    =>  $audio->getPerformer(),
                        'title'     =>  $audio->getTitle()
                    ]) : null
                ]),
            ] + $data);

            Request::sendChatAction(['action' => 'upload_audio'] + $data);

            $song = new Song();
            $song->loadAudio($audio);

            if($audio->getFileSize() <= \Yii::$app->params['maxFileSize']){
                try{
                    $filepath = TrackDownloader::getUrl($audio->getFileId());

                    try{
                        if ($fp_remote = fopen($filepath, 'rb')) {
                            $localtempfilename = tempnam('/tmp', 'getID3'.time());
                            if ($fp_local = fopen($localtempfilename, 'wb')) {
                                while ($buffer = fread($fp_remote, 8192)) {
                                    fwrite($fp_local, $buffer);
                                }
                                fclose($fp_local);
                                $getID3 = new \getID3;
                                $fileInfo = $getID3->analyze($localtempfilename);
                                unlink($localtempfilename);
                            }
                            fclose($fp_remote);
                        }

                        if(!empty($fileInfo)){
                            $tags = TagExtractor::getInfo($fileInfo);
                            \Yii::trace($tags);
                            $song->loadTags($tags);
                        }
                    }catch (ErrorException $e){
                        \Yii::trace($e->getMessage());
                    }
                }catch (\Exception $exception){
                    return Request::sendMessage([
                        'text' => \Yii::t('general', 'Произошла ошибка при попытке получить ссылку на файл!'),
                        'reply_to_message_id'   =>  $message->getMessageId()
                    ] + $data);
                }
            }

            if($this->botUser){
                $song->user_id = $this->botUser->id;
            }

            if(empty($song->title)){
                $song->title = \Yii::t('general', 'Неизвестная композиция');
            }

            if(empty($song->artist)){
                $song->artist = \Yii::t('general', 'Неизвестный исполнитель');
            }

            if($song->save()){
                if($this->botUser){
                    $this->botUser->addTrack($song);
                }

                return Request::sendMessage([
                    'text'      => \Yii::t('general', 'Трек "{artist} - {title}" успешно добавлен!', [
                        'artist'    => $song->artist,
                        'title'     => $song->title
                    ]),
                ] + $data);
            }else{
                return Request::sendMessage([
                    'text'      =>  \Yii::t('general', 'Произошла ошибка при попытке сохранить в базу трек. {errors}', [
                        'title' => $song->title,
                        'errors' => $this->telegram->isAdmin() ? ' Ошибки: '.json_encode($song->getErrors(), JSON_UNESCAPED_UNICODE) : null
                    ]),
                    'reply_to_message_id'   =>  $message->getMessageId()
                ] + $data);
            }
        }else if($message->getText(true) && $message->getChat()->isPrivateChat()){
            return Request::sendMessage([
                'text'                  =>  \Yii::t('general', 'Вы хотели найти трек? Для этого воспользуйтесь inline режимом работы бота. Введите @MuzOneBot <название трека или исполнитель> (например, `@MuzOneBot {content}`), и выберите во всплывающем окне нужный вам трек.', [
                    'content' => $message->getText(true)
                ]),
                'parse_mode'            =>  'Markdown',
                'reply_to_message_id'   =>  $message->getMessageId()
            ] + $data);
        }

        return Request::emptyResponse();
    }
}
