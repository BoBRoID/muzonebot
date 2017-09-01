<?php

namespace backend\modules\translations\controllers;

use common\models\Language;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

/**
 * Default controller for the `translations` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     * @throws \yii\base\InvalidParamException
     */
    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' =>  Language::find()->where(['status' => Language::STATUS_ACTIVE])
        ]);

        return $this->render('index', [
            'dataProvider'   =>  $dataProvider
        ]);
    }

    public function actionEdit($id): string
    {

    }


}
