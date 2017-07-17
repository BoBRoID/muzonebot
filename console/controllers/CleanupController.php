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
use common\models\UserToken;
use yii\console\Controller;

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

    public function actionSongs(){
        $total = $deleted = 0;
        /**
         * @var $song Song
         */
        foreach(Song::find()->where(['deleted' => 0])->each() as $song){
            $total++;
            try{
                TrackDownloader::getUrl($song->fileId);
            }catch (\Exception $e){
                $deleted++;
                $song->deleted = 1;
                $song->save(false);
            }
        }

        echo 'Total worked with '.$total.' tracks, deleted '.$deleted;
    }

}