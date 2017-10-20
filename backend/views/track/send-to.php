<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 20.10.17
 * Time: 18:53
 *
 * @var $model \backend\models\FileToChatForm
 */


$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($model, 'fileId'),
     $form->field($model, 'chatId'),
     $form->field($model, 'caption');

echo \yii\bootstrap\Html::tag('div', \yii\bootstrap\Html::submitButton('Submit', ['class' => 'btn btn-default']), ['class' => 'text-center']);

$form::end();