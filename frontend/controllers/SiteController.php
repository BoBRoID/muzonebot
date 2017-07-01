<?php

namespace frontend\controllers;


use common\helpers\TrackDownloader;
use common\models\User;
use Yii;
use frontend\models\forms\SongSearch;
use common\models\Song;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use common\models\UserToken;
use yii\data\ActiveDataProvider;
use common\models\Chat;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

        if(!\Yii::$app->session->get('tgAuthToken', false)){
            \Yii::$app->session->set('tgAuthToken', \Yii::$app->security->generateRandomString(64));
        }
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' =>  Song::find()->with('userSong'),
            'sort'  =>  [
                'defaultOrder'  =>  [
                    'added' => SORT_DESC
                ]
            ],
            'pagination' => [
                'pageSize'  => 10,
                'page'      =>  0,
            ],
        ]);

        return $this->render('index', [
            'songs'         =>  $dataProvider,
            'searchModel'   =>  new SongSearch()
        ]);
    }

    public function actionSearch(){
        $searchModel = new SongSearch();
        $searchModel->load(\Yii::$app->request->get());

        $dataProvider = new ActiveDataProvider([
            'query' =>  $searchModel->getResults()->with('userSong')
        ]);

        return $this->render('search', [
            'searchModel'   =>  $searchModel,
            'dataProvider'  =>  $dataProvider
        ]);
    }

    public function actionGetTrack($id){
        $song = Song::findOne(['id' => $id]);

        if(!$song){
            throw new NotFoundHttpException();
        }

        try{
            $songData = TrackDownloader::get($song->fileId);
        }catch (\Exception $exception){
            throw new NotFoundHttpException(\Yii::t('site', 'Не удалось получить трек!'));
        }

        return \Yii::$app->response->sendContentAsFile($songData, Inflector::transliterate($song->artist.' - '.$song->title).'.mp3');
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        $authToken = \Yii::$app->session->get('tgAuthToken', false);

        if(Yii::$app->user->logout()){
            UserToken::deleteAll(['token' => $authToken]);
            \Yii::$app->session->remove('tgAuthToken');
        }

        return $this->goHome();
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Displays login page.
     *
     * @return string
     */
    public function actionLogin()
    {
        return $this->render('login');
    }

    /**
     * @return string
     */
    public function actionStats(){
        $chatsStats = Chat::find()
            ->select(['type', 'count' => new Expression('COUNT(`type`)')])
            ->groupBy('type')
            ->asArray()
            ->all();

        $topUploaderStats = Song::find()
            ->select(['count' => new Expression('COUNT(`s`.`id`)'), 'u.username', 'u.first_name', 'u.last_name'])
            ->from(['s' => Song::tableName(), 'u' => User::tableName()])
            ->where(['not', ['s.user_id' => 0]])
            ->where('s.user_id = u.id')
            ->groupBy('s.user_id')
            ->orderBy('count DESC')
            ->limit(10)
            ->asArray()
            ->all();

        $chatsCount = array_sum(ArrayHelper::getColumn($chatsStats, 'count'));

        array_walk($chatsStats, function(&$item){
            switch($item['type']){
                case 'channel':
                    $item['type'] = \Yii::t('chats_types', 'Каналов');
                    break;
                case 'private':
                    $item['type'] = \Yii::t('chats_types', 'Пользователей');
                    break;
                case 'group':
                    $item['type'] = \Yii::t('chats_types', 'Групп');
                    break;
                case 'supergroup':
                    $item['type'] = \Yii::t('chats_types', 'Супергрупп');
                    break;
            }
        });

        return $this->render('stats', [
            'chatsStats'        =>  $chatsStats,
            'chatsCount'        =>  $chatsCount,
            'topUploaderStats'  =>  $topUploaderStats
        ]);
    }

    /**
     * @return string
     */
    public function actionIsGuest(){
        \Yii::$app->response->format = 'json';

        return \Yii::$app->user->isGuest ? : Url::to([\Yii::$app->request->referrer, 'language' => \Yii::$app->user->identity->language_code]);
    }
}
