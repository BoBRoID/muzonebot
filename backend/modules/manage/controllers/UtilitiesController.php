<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 6/29/17
 * Time: 11:33 PM
 */

namespace app\modules\manage\controllers;


use app\helpers\TagExtractor;
use app\modules\manage\models\GetTagsForm;
use yii\filters\AccessControl;
use yii\web\Controller;

class UtilitiesController extends Controller
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
        return $this->redirect('/manage/utilities/get-tags');
    }

    public function actionGetTags(){
        $form = new GetTagsForm();

        $fileHash = null;
        $tags = $parsedInfo = [];

        if(\Yii::$app->request->post($form->formName()) && $form->load(\Yii::$app->request->post())){
            $tags = $form->getTags();
            $parsedInfo = TagExtractor::getInfo($tags);
            $fileHash = $form->fileHash;
        }

        return $this->render('get-tags', [
            'model'     =>  $form,
            'fileInfo'  =>  $this->array_utf8_encode($tags),
            'parsedInfo'=>  $parsedInfo,
            'fileHash'  =>  $fileHash
        ]);
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