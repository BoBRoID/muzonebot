<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 26.06.2017
 * Time: 13:15
 */

namespace console\controllers;


use common\models\AdminToken;
use common\models\UserToken;
use yii\console\Controller;

class CleanupController extends Controller
{

    public function actionMessages(){
        \Yii::$app->db->createCommand()->delete('message', ['<=', 'date', date('Y-m-d 00:00:00')]);
    }

    public function actionTokens(){
        \Yii::$app->db->createCommand()->delete(UserToken::tableName(), ['<=', 'expire', time()]);
    }

    public function actionAdminTokens(){
        \Yii::$app->db->createCommand()->delete(AdminToken::tableName(), ['<=', 'expire', time()]);
    }

    public function actionSongsLinks(){

    }

}