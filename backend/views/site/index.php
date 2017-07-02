<?php
$this->title = \Yii::t('manage', 'Управление системой');

$this->params['breadcrumbs'][] = $this->title;

$managerBlocks = [
    [
        'title' =>  \Yii::t('manage', 'Управление пользователями'),
        'url'   =>  \yii\helpers\Url::to(['/manage/default/users'])
    ],
    [
        'title' =>  \Yii::t('manage', 'Управление жанрами'),
        'url'   =>  \yii\helpers\Url::to(['/manage/default/genres'])
    ],
    [
        'title' =>  \Yii::t('manage', 'Управление треками'),
        'url'   =>  \yii\helpers\Url::to(['/manage/default/tracks'])
    ],
    [
        'title' =>  \Yii::t('manage', 'Управление альбомами'),
        'url'   =>  \yii\helpers\Url::to(['/manage/default/albums'])
    ],
    [
        'title' =>  \Yii::t('manage', 'Управление отзывами'),
        'url'   =>  \yii\helpers\Url::to(['/manage/default/feedbacks'])
    ],
    [
        'title' =>  \Yii::t('manage', 'Управление переводами'),
        'url'   =>  \yii\helpers\Url::to(['/translatemanager/language/list'])
    ],
];

?>
<h1><?=$this->title?></h1>
<hr>
<div class="row">
    <?php foreach($managerBlocks as $block){ ?>
    <div class="col-xs-12 col-md-6 col-lg-4 col-xl-3">
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <?=$block['title']?>
                <br>
                <br>
                <a href="<?=$block['url']?>" class="btn btn-block btn-success"><?=\Yii::t('manage', 'Перейти')?></a>
            </div>
        </div>
    </div>
    <?php } ?>
</div>