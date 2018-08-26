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

    public function actionSongs(){
        $total = $deleted = 0;
        /**
         * @var $song Song
         */
        foreach(Song::find()->where(['deleted' => 0, 'isBig' => 0])->andWhere(['like', 'title', 'file_%', false])->andWhere(['<=', 'last_update', time() - 3600])->each(5) as $song){
            $total++;

            try{
                TrackDownloader::getUrl($song->fileId);
            }catch (NotFoundHttpException $e){
                $deleted++;
                $song->deleted = 1;
            }catch (BadRequestHttpException $e){
                $song->isBig = 1;
            }

            $song->save(false);
        }
        /**
         * @var $song Song
         */
        foreach(Song::find()->where(['deleted' => 0, 'isBig' => 0])->andWhere(['<=', 'last_update', time() - 3600])->each(5) as $song){
            $total++;

            try{
                TrackDownloader::getUrl($song->fileId);
            }catch (NotFoundHttpException $e){
                $deleted++;
                $song->deleted = 1;
            }catch (BadRequestHttpException $e){
                $song->isBig = 1;
            }

            $song->save(false);
        }

        echo 'Total worked with '.$total.' tracks, deleted '.$deleted;
    }

}