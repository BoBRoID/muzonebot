<?php
namespace tg\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{

    public $layout = '@app/../frontend/view/layouts/main.php';
    public $viewPath = '@app/../frontend/view/';

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
        throw new NotFoundHttpException();
    }

}
