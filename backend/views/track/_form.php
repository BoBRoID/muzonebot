<?php
/**
 * @var $trackForm \backend\models\forms\TrackForm
 */
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

$params = ['layout' => 'horizontal'];

$buttons = [];
$buttons[] = Html::submitButton(\Yii::t('manage', 'Сохранить изменения'), ['class' => 'btn btn-outline-success btn-lg mb-2']);
//$buttons[] = Html::a(\Yii::t('manage', 'К результатам поиска'), Url::previous('tracks'), ['class' => 'btn btn-outline-primary mt-2']);

if(\Yii::$app->request->isAjax){
    $params['options']['data-pjax'] = 1;
}

$params['fieldConfig']['horizontalCssClasses']['label'] = 'col-sm-4 offset-sm-1';
$params['fieldConfig']['horizontalCssClasses']['wrapper'] = 'col-sm-6';

$form = ActiveForm::begin($params);

echo $form->field($trackForm, 'title'),
$form->field($trackForm, 'artist'),
$form->field($trackForm, 'duration'),
$form->field($trackForm, 'genreID');

echo Html::tag('div', implode(\Yii::t('manage', '{br} или {br}', ['br' => Html::tag('br')]), $buttons), ['class' => 'text-center']);

$form::end();