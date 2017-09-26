<?php
namespace api\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
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
