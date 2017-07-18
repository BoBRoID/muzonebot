<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 03.07.2017
 * Time: 17:27
 * @var $model \frontend\models\forms\TrackForm
 */
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->title = \Yii::t('site', 'Редактирование трека');

$this->params['breadcrumbs'][] = [
    'label' =>  \Yii::t('site', 'Мои треки'),
    'url'   =>  ['/tracks/my']
];
$this->params['breadcrumbs'][] = $this->title;

$formOptions = ['layout' => 'horizontal'];

if(\Yii::$app->request->isAjax){
    $formOptions['options']['data-pjax'] = 1;
    $formOptions['fieldConfig']['horizontalCssClasses']['label'] = 'col-sm-4 offset-sm-1';
    $formOptions['fieldConfig']['horizontalCssClasses']['wrapper'] = 'col-sm-6';
}

if(\Yii::$app->session->getFlash('messages', false)){
    foreach(\Yii::$app->session->getFlash('messages') as $message){
        echo \yii\bootstrap\Alert::widget([
            'options'   =>  [
                'class' =>  'alert-'.$message['type']
            ],
            'body'  =>  $message['message']
        ]);
    }
}

$form = ActiveForm::begin($formOptions);

echo $form->field($model, 'title'),
    $form->field($model, 'artist');

//echo $form->field($model, 'genreID')->dropDownList([])'

echo \yii\helpers\Html::tag('div', Html::submitButton(\Yii::t('site', 'Сохранить'), ['class' => 'btn btn-success']), ['class' => 'text-center']);

$form::end();