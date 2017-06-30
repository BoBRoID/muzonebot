<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 26.06.2017
 * Time: 13:15
 */

namespace app\commands;


use app\models\UserToken;
use yii\console\Controller;
use yii\db\Query;

class CleanupController extends Controller
{

    public function actionMessages(){
        \Yii::$app->db->createCommand()->delete('message', ['<=', 'date', date('Y-m-d 00:00:00')]);
    }

    public function actionTokens(){
        \Yii::$app->db->createCommand()->delete(UserToken::tableName(), ['<=', 'expire', time()]);
    }

    public function actionSongsLinks(){

    }

}