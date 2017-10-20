<?php
/**
 * @var $newMembersToday int
 * @var $activeMembersToday int
 * @var $newTracksToday int
 * @var $lastAddedTrack \common\models\Song
 */

$this->title = \Yii::t('manage', 'Управление системой');
\backend\assets\DashboardAsset::register($this);
$this->params['breadcrumbs'][] = $this->title;
?>
<h4>Сегодняшняя статистика</h4>
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
                    <span class="one-line"><?=\Yii::t('manage', 'Последний: {track}', [
                            'track' =>  $lastAddedTrack->title.' - '.$lastAddedTrack->artist
                        ])?></span>
                    <br>
                    <span class="one-line"><?=\Yii::t('manage', 'Добавил: {username} {date}', [
                            'date'      =>  \Yii::$app->formatter->asRelativeTime($lastAddedTrack->added),
                            'username'  =>  $lastAddedTrack->user ? $lastAddedTrack->user->username : null
                        ])?></span>
                </p>
                <?php } ?>
            </div>
        </div>
    </div>
    <!--/.col-->

</div>
<!--/.row-->