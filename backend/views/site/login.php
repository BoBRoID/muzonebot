<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
$hash = \Yii::$app->session->get('adminTgAuthToken');
$url = 'https://telegram.me/muzonebot?start='.$hash.'-adm';
?>
<div class="text-center" style="height: 100%;">
    <a href="<?=$url?>" target="_blank" class="btn btn-lg btn-default" style="margin-top:10%;">Login <?=\rmrevin\yii\fontawesome\FA::i('telegram')?></a>
</div>