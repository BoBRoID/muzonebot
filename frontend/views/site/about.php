<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = \Yii::t('site', 'О сервисе');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?=\Yii::t('site', 'Пока что мы наполняем данную страницу. Вернитесь сюда позже.')?>
    </p>
</div>
