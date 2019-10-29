<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 26.06.2017
 * Time: 13:15
 */

namespace console\controllers;


use common\helpers\TrackDownloader;
use common\models\AdminToken;
use common\models\Song;
use common\models\TelegramFile;
use common\models\UserToken;
use console\helpers\Messages;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use yii\console\Controller;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class CleanupController extends Controller
{

    public function actionMessages(){
        \Yii::$app->db->createCommand()->delete('message', ['<=', 'date', date('Y-m-d 00:00:00')])->execute();
    }

    public function actionTokens(){
        \Yii::$app->db->createCommand()->delete(UserToken::tableName(), ['<=', 'expire', time()])->execute();
    }

    public function actionAdminTokens(){
        \Yii::$app->db->createCommand()->delete(AdminToken::tableName(), ['<=', 'expire', time()])->execute();
    }

    public function actionSongsLinks(){

    }

    public function actionSongs(bool $debug = false): void
    {
        $proceed = $deleted = 0;

        $query = Song::find()->where(['deleted' => 0, 'isBig' => 0])->andWhere(['<=', 'last_update', time() - 3600]);

        $count = $query->count();

        if ($debug) {
            Messages::pretify("Found {$count} songs, let's check them...");
        }

        /**
         * @var $song Song
         */
        foreach($query->each(5) as $song){
            $proceed++;

            if ($debug) {
                Messages::pretify("Now checking song ID {$song->id} (#{$proceed} from {$count})...");
            }

            try {
                $file = TelegramFile::getFreshByFileId($song->fileId);

                if (!$file->checkIsAvailable()) {
                    throw new NotFoundHttpException();
                }
            } catch (NotFoundHttpException $e) {
                $deleted++;
                $song->deleted = 1;

                if ($debug) {
                    Messages::pretify('It seems unreachable, deleting...');
                }
            } catch (BadRequestHttpException $e) {
                $song->isBig = 1;

                if ($debug) {
                    Messages::pretify('It seems unreachable, marking as big...');
                }
            }

            $song->save(false);
        }

        if ($debug) {
            Messages::pretify("Totally proceed {$proceed} songs, removed {$deleted}");
        }
    }

}