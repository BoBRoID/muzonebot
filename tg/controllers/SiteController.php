<?php
namespace tg\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{

    public $layout = '@app/../frontend/views/layouts/main.php';
    public $viewPath = '@app/../frontend/views/site/';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'hook'  =>  [
                'class' =>  'tg\actions\WebHookAction'
            ],
            'set'  =>  [
                'class' =>  'tg\actions\SetAction'
            ]
        ];
    }

    /**
     * Displays homepage.
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {
        \Yii::setAlias('@app', \Yii::getAlias('@app/../frontend/'));

        throw new NotFoundHttpException();
    }

}
