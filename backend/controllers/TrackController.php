<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 6/26/17
 * Time: 8:10 PM
 */

namespace backend\controllers;


use backend\models\forms\SongSearch;
use common\helpers\TrackDownloader;
use backend\models\Song;
use backend\models\forms\TrackForm;
use ErrorException;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TrackController extends Controller
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

        Url::remember(\Yii::$app->request->url, 'tracks');

        return $this->render('index', [
            'dataProvider'  =>  $dataProvider,
            'searchModel'   =>  $searchModel
        ]);
    }


    public function actionEdit($id){
        $track = Song::findOne($id);

        if(!$track){
            throw new NotFoundHttpException(\Yii::t('manage', 'Трек не найден!'));
        }

        $trackForm = new TrackForm();
        $trackForm->setAttributes([
            'id'        =>  $track->id,
            'title'     =>  $track->title,
            'artist'    =>  $track->artist,
            'duration'  =>  $track->duration,
            'genreID'   =>  $track->genreID
        ]);

        if(\Yii::$app->request->post($trackForm->formName()) && $trackForm->load(\Yii::$app->request->post())){
            if($trackForm->save()){
                \Yii::$app->session->addFlash('success', \Yii::t('manage', 'Трек успешно отредактирован!'));
            }else{
                \Yii::$app->session->addFlash('danger', \Yii::t('manage', 'Произошли ошибки при редактировании: {errors}!', ['errors' => \GuzzleHttp\json_encode($trackForm->getErrors())]));
            }
        }

        if(\Yii::$app->request->isAjax){
            return $this->renderAjax('edit', [
                'trackForm' =>  $trackForm
            ]);
        }

        return $this->render('edit', [
            'trackForm' =>  $trackForm
        ]);
    }

    public function actionRemove($id){
        if(!\Yii::$app->request->isAjax){
            throw new BadRequestHttpException();
        }

        $track = Song::findOne($id);

        if(!$track){
            throw new NotFoundHttpException(\Yii::t('manage', 'Трек не найден!'));
        }

        $track->deleted = 1;
        return $track->save();
    }

    public function actionGetMetadata($id){
        if(!\Yii::$app->request->isAjax){
            throw new BadRequestHttpException();
        }

        \Yii::$app->response->format = 'json';

        $song = Song::findOne(['id' => $id]);

        if(!$song){
            throw new NotFoundHttpException();
        }

        $fileInfo = [];

        try {
            if ($fp_remote = fopen(TrackDownloader::getUrl($song->fileId), 'rb')) {
                $localtempfilename = '/tmp/getID3' . time().rand(0, 9999).'.mp3';

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
        } catch (ErrorException $e) {
            $fileInfo = $e->getMessage();
        }

        return $this->array_utf8_encode($fileInfo);
    }

    public function array_utf8_encode($dat)
    {
        if (is_string($dat))
            return utf8_encode($dat);
        if (!is_array($dat))
            return $dat;
        $ret = array();
        foreach ($dat as $i => $d)
            $ret[$i] = self::array_utf8_encode($d);
        return $ret;
    }
}