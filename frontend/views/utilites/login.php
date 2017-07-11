<?php
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\Html;

$hash = \Yii::$app->session->get('tgAuthToken');
$url = 'https://telegram.me/muzonebot?start='.$hash;
?>
<?=\Yii::t('site', 'Для авторизации, пожалуйста, нажмите на кнопку ниже, затем нажмите START в диалоге с ботом в вашем клиенте telegram')?>
<br>
<br>
<div class="text-center">
    <?=Html::a(\Yii::t('site', 'Авторизоваться через telegram').'&nbsp;'. FA::i('telegram'), $url, ['target' => '_blank', 'class' => 'btn btn-secondary'])?>
</div>
<br>
<?=\Yii::t('site', 'или отправьте боту следующую команду')?>
<br>
<br>
<blockquote>
    <small>/start <?=$hash?></small>
</blockquote>