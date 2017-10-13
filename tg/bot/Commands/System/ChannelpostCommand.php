<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use common\helpers\TagExtractor;
use common\helpers\TrackDownloader;
use common\models\Song;
use Longman\TelegramBot\Request;
use tg\bot\Base\BaseSystemCommand;

/**
 * Channel post command
 */
class ChannelpostCommand extends BaseSystemCommand
{
    /**
     * @var string
     */
    protected $name = 'channelpost';

    /**
     * @var string
     */
    protected $description = 'Handle channel post';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    protected $need_mysql = true;

    /**
     * Execution if MySQL is required but not available
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute(){
        $message = $this->getChannelPost();

        if($audio = $message->getAudio()){
            if($song = Song::findOne(['fileId' => $audio->getFileId()])){
                return Request::emptyResponse();
            }

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
                            $song->loadTags($tags);
                        }
                    }catch (\ErrorException $e){
                        \Yii::trace($e->getMessage());
                    }

                    $fileName = preg_replace('/(^(.*)\/|\.\w+$)/', '', urldecode($filepath));
                    $fileNameParts = explode('-', $fileName);

                    if(empty($song->title)){
                        if(count($fileNameParts) === 1){
                            $song->title = trim($fileNameParts[0]);
                        }else{
                            $song->title = trim($fileNameParts[1]);

                            if(empty($song->artist)){
                                $song->artist = trim($fileNameParts[0]);
                            }
                        }
                    }
                }catch (\Exception $exception){
                    return Request::emptyResponse();
                }
            }

            if($this->botUser){
                $song->user_id = $this->botUser->id;
            }

            $song->title = $song->title ? : \Yii::t('general', 'Неизвестная композиция');
            $song->artist = $song->artist ? : \Yii::t('general', 'Неизвестный исполнитель');

            if($song->save()){
                $this->botUser->addTrack($song);
            }
        }

        return Request::emptyResponse();
    }
}
