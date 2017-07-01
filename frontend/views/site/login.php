<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 7/1/17
 * Time: 12:02 AM
 */
use yii\bootstrap\Html;


$hash = \Yii::$app->session->get('tgAuthToken');
$url = 'https://telegram.me/muzonebot?start='.$hash;

?>
<?=\Yii::t('site', 'Для авторизации, пожалуйста, нажмите на кнопку ниже, затем нажмите START в диалоге с ботом в вашем клиенте telegram')?>
<br>
<br>
<div class="text-center">
    <?=Html::a(\Yii::t('site', 'Авторизоваться через telegram'), $url, ['target' => '_blank', 'class' => 'btn btn-default'])?>
</div>
<br>
<?=\Yii::t('site', 'или отправьте боту следующую команду')?>
<br>
<br>
<blockquote>
    <small>/start <?=$hash?></small>
</blockquote>
<b><?=\Yii::t('site', 'Важно!')?></b> <?=\Yii::t('site', 'Не закрывайте это модальное окно пока бот не ответит вам что вы успешно авторизованы. В инном случае вам придётся после ответа обновить страницу самостоятельно!')?>