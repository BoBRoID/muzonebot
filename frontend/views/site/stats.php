<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 26.06.2017
 * Time: 14:03
 *
 * @var $chatsStats array
 * @var $topUploaderStats array
 */

use dosamigos\chartjs\ChartJs;

$this->title = \Yii::t('site', 'Статистика сервиса');
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?=$this->title?></h1>
<div class="row">
    <div class="col-12">
        <h2 class="text-center"><?=\Yii::t('site', 'Топ пользователей')?></h2>
    </div>
    <div class="col-12">
        <div class="row">
            <div class="col-12 col-md-3">
                <ul class="list-group">
                    <li class="list-group-item list-group-item-info">
                        <h5 class="list-group-item-heading"><?=\Yii::t('site', 'Кто пользуется ботом?')?></h5>
                    </li>
                    <?php foreach($chatsStats as $statArray){ ?>
                        <li class="list-group-item justify-content-between">
                            <?=$statArray['type']?>
                            <span class="badge badge-default badge-pill"><?=$statArray['count']?></span>
                        </li>
                    <?php } ?>
                </ul>

                <!-- <ul class="list-group">
            <li class="list-group-item list-group-item-info">
                <h4 class="list-group-item-heading"><?=\Yii::t('site', 'Сколько в нём треков?')?></h4>
            </li>
            <?php foreach($chatsStats as $statArray){ ?>
            <li class="list-group-item">
                <span class="badge"><?=$statArray['count']?></span>
                <?=$statArray['type']?>
            </li>
            <?php } ?>
        </ul> -->
            </div>
            <div class="col-12 col-md-9">
                <?php
                $labels = [];
                $dataset = new stdClass();
                $dataset->backgroundColor = \Colors\RandomColor::many(count($topUploaderStats), [
                    'hue'           =>  'purple yellow',
                    'luminosity'    =>  'light',
                ]);

                foreach($topUploaderStats as $stat){
                    $dataset->data[] = $stat['count'];
                    $labels[] = $stat['username'] ? : $stat['first_name'].' '.$stat['last_name'];
                }

                echo ChartJs::widget([
                    'type'  =>  'pie',
                    'clientOptions'   =>  [
                        'legend'=>  [
                            'position'  =>  'right'
                        ],
                    ],
                    'options'   =>  [
                        'class' =>  'd-block d-md-none'
                    ],
                    'data'  =>  [
                        'datasets'  =>  [
                            $dataset
                        ],
                        'labels'    =>  $labels
                    ]
                ]);

                echo ChartJs::widget([
                    'type'  =>  'pie',
                    'clientOptions'   =>  [
                        'legend'=>  [
                            'position'  =>  'bottom'
                        ],
                    ],
                    'options'   =>  [
                        'class' =>  'd-none d-md-block',
                        'height'    =>  '300px'
                    ],
                    'data'  =>  [
                        'datasets'  =>  [
                            $dataset
                        ],
                        'labels'    =>  $labels
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
</div>