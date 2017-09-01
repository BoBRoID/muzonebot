<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 01.09.2017
 * Time: 14:01
 */

namespace backend\modules\translations\controllers;


use common\models\Language;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;

class MessagesController extends Controller
{

    public function actionIndex($id){
        $language = Language::findOne(['language_id' => $id]);

        if(!$language){
            \Yii::$app->session->addFlash('warning', \Yii::t('manage', 'Выбраный вами язык не найден! Выберите язык из списка!'));
            return \Yii::$app->response->redirect(Url::to(['/translations/default/index']));
        }

        $dataProvider = new ActiveDataProvider([
            'query' =>  $language->getMessages()
        ]);

        return $this->render('index');
    }

}