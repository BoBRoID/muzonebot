<?php

namespace backend\modules\manage\controllers;

use common\models\User;
use app\modules\manage\models\SongSearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Default controller for the `manage` module
 */
class DefaultController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionUsers(){
        $dataProvider = new ActiveDataProvider([
            'query' =>  User::find()
        ]);

        return $this->render('users', [
            'dataProvider'  =>  $dataProvider
        ]);
    }

    public function actionTracks(){
        $searchModel = new SongSearch;
        $searchModel->load(\Yii::$app->request->get());

        $dataProvider = new ActiveDataProvider([
            'query' =>  $searchModel->getResults()->with('user'),
            'sort'  =>  [
                'defaultOrder'  =>  [
                    'id'    =>  SORT_DESC
                ]
            ]
        ]);

        return $this->render('tracks', [
            'dataProvider'  =>  $dataProvider,
            'searchModel'   =>  $searchModel
        ]);
    }

    public function actionGenres(){

    }

    public function actionAlbums(){

    }

    public function actionFeedbacks(){

    }
}
