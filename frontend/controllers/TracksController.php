<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 29.06.2017
 * Time: 13:23
 */

namespace frontend\controllers;


use frontend\models\forms\SongSearch;
use frontend\models\Song;
use common\models\UserSongs;
use frontend\models\forms\TrackForm;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TracksController extends Controller
{

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

    public function actionIndex(){
        return $this->redirect('/tracks/my');
    }

    public function actionMy(){
        $searchModel = new SongSearch();
        $searchModel->load(\Yii::$app->request->get());

        $dataProvider = new ActiveDataProvider([
            'query' =>  $searchModel->getResults()
                ->leftJoin(['us' => UserSongs::tableName()], 'us.song_id = songs.id')
                ->andWhere(['us.user_id' => \Yii::$app->user->id]),
            'sort'  =>  [
                'defaultOrder'  =>  [
                    'added' =>  SORT_DESC
                ]
            ]
        ]);

        return $this->render('my', [
            'dataProvider'  =>  $dataProvider,
            'searchModel'   =>  $searchModel
        ]);
    }

    public function actionToggleMy(){
        if(\Yii::$app->request->isAjax === false){
            throw new BadRequestHttpException('only ajax');
        }

        \Yii::$app->response->format = 'json';

        if(!$trackId = \Yii::$app->request->post('trackId')){
            throw new InvalidParamException('trackId is required');
        }

        /**
         * @var Song $song
         */
        $song = Song::find()->where(['id' => $trackId])->with('userSong')->one();

        if(!$song){
            throw new NotFoundHttpException('song not found');
        }

        if($song->userSong){
            if(!$song->userSong->delete()){
                throw new \RuntimeException('cant remove user song');
            }

            return ['result' => 'success', 'state' => 'deleted', 'message' => \Yii::t('site', 'Добавить в мои треки')];
        }

        if(!\Yii::$app->user->getIdentity()->addTrack($song)){
            throw new \RuntimeException('cant add user song');
        }

        return ['result' => 'success', 'state' => 'added', 'message' => \Yii::t('site', 'Убрать из моих треков')];
    }

    public function actionEdit($id){
        $song = Song::findOne(['id' => $id]);

        if(!$song || (int)$song->user_id !== (int)\Yii::$app->user->identity->getId()){
            throw new NotFoundHttpException('its not your track');
        }

        $form = new TrackForm();
        $form->load([$form->formName() => $song->toArray()]);
        $form->trackID = $song->id;

        if(\Yii::$app->request->post($form->formName()) && $form->load(\Yii::$app->request->post())){
            if($form->save()){
                \Yii::$app->session->addFlash('messages', ['message' => \Yii::t('site', 'Трек успешно отредактирован!'), 'type' => 'success']);
            }else{
                \Yii::trace($form->getErrors());
                \Yii::$app->session->addFlash('messages', ['message' => \Yii::t('site', 'Произошли ошибки при редактировании трека!'), 'type' => 'danger']);
            }
        }

        if(\Yii::$app->request->isAjax){
            return $this->renderAjax('edit', [
                'model' =>  $form
            ]);
        }

        return $this->render('edit', [
            'model' =>  $form
        ]);
    }

}