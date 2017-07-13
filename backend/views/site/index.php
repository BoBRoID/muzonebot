<?php
/**
 * @var $newMembersToday int
 * @var $activeMembersToday int
 * @var $newTracksToday int
 * @var $lastAddedTrack \common\models\Song
 */
$this->title = \Yii::t('manage', 'Управление системой');

$this->params['breadcrumbs'][] = $this->title;

$managerBlocks = [
    [
        'title' =>  \Yii::t('manage', 'Управление пользователями'),
        'url'   =>  \yii\helpers\Url::to(['/site/users'])
    ],
    [
        'title' =>  \Yii::t('manage', 'Управление жанрами'),
        'url'   =>  \yii\helpers\Url::to(['/site/genres'])
    ],
    [
        'title' =>  \Yii::t('manage', 'Управление треками'),
        'url'   =>  \yii\helpers\Url::to(['/site/tracks'])
    ],
    [
        'title' =>  \Yii::t('manage', 'Управление альбомами'),
        'url'   =>  \yii\helpers\Url::to(['/site/albums'])
    ],
    [
        'title' =>  \Yii::t('manage', 'Управление отзывами'),
        'url'   =>  \yii\helpers\Url::to(['/site/feedback'])
    ],
    [
        'title' =>  \Yii::t('manage', 'Управление переводами'),
        'url'   =>  \yii\helpers\Url::to(['/translatemanager/language/list'])
    ],
    [
        'title' =>  \Yii::t('manage', 'Распознавалка тэгов'),
        'url'   =>  \yii\helpers\Url::to(['/utilities/get-tags'])
    ],
];

?>

<div class="row">
    <div class="col-md-4 col-12">
        <div class="card card-inverse card-primary">
            <div class="card-block pb-0">
                <h4 class="mb-0"><?=$newMembersToday?></h4>
                <p><?=\Yii::t('manage', 'Новых пользователей')?></p>
            </div>
            <div class="chart-wrapper px-3" style="height:70px;">
                <canvas id="card-chart1" class="chart" height="70"></canvas>
            </div>
        </div>
    </div>
    <!--/.col-->

    <div class="col-md-4 col-12">
        <div class="card card-inverse card-info">
            <div class="card-block pb-0">
                <h4 class="mb-0"><?=$activeMembersToday?></h4>
                <p><?=\Yii::t('manage', 'Активных пользователей')?></p>
            </div>
            <div class="chart-wrapper px-3" style="height:70px;">
                <canvas id="card-chart2" class="chart" height="70"></canvas>
            </div>
        </div>
    </div>
    <!--/.col-->

    <div class="col-md-4 col-12">
        <div class="card card-inverse card-primary">
            <div class="card-block pb-0">
                <h4 class="mb-0 one-line"><?=$newTracksToday?></h4>
                <p><?=\Yii::t('manage', 'Треков')?></p>
            </div>
            <div class="chart-wrapper" style="height:70px;">
                <?php if($newTracksToday && $lastAddedTrack){ ?>
                <p class="px-3 ml-1">
                    Last track: choo choo motherfucker - major pain<br>
                    added by: major pain, on 07.07.2017 14:47
                </p>
                <?php } ?>
            </div>
        </div>
    </div>
    <!--/.col-->

</div>
<!--/.row-->
<h1><?=$this->title?></h1>
<hr>
<div class="row">
    <?php foreach($managerBlocks as $block){ ?>
    <div class="col-12 col-md-6 col-lg-4 col-xl-3">
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