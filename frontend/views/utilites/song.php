<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 29.06.2017
 * Time: 14:59
 * @var $model \common\models\Song
 * @var $searchModel \frontend\models\forms\SongSearch
 */
$searchLink = isset($searchLink) ? $searchLink : '/site/search';
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\Html;
?>
<div class="d-flex flex-column align-items-stretch w-100 flex-nowrap">
    <div class="d-flex flex-row justify-content-between w-100">
        <div class="d-flex">
            <?=Html::a(
                $model->title,
                [
                    $searchLink,
                    $searchModel->formName() => ['trackName' => $model->title]
                ],
                [
                    'title'     => \Yii::t('site', 'Искать треки с названием {name}', ['name' => $model->title]),
                    'data-pjax' =>  'false'
                ])?>&nbsp;
            -&nbsp;
            <?=Html::a(
                $model->artist,
                [
                    $searchLink,
                    $searchModel->formName() => ['artist' => $model->artist]
                ],
                [
                    'title'     => \Yii::t('site', 'Искать треки исполнителя {name}', ['name' => $model->artist]),
                    'data-pjax' =>  'false'
                ]
            )?>
        </div>
        <div class="d-flex">
            <div class="btn-group btn-group-sm" role="group" aria-label="<?=\Yii::t('site', 'Действия с треком')?>">
                <?=Html::button(FA::i('play'), ['class' => 'btn btn-secondary btn-xs listenTrack', 'data-id' => $model->id])?>
                <?=Html::a(
                    FA::i('download'),
                    [
                        '/site/get-track',
                        'id' => $model->id
                    ],
                    [
                        'class'     => 'btn btn-secondary btn-xs',
                        'title'     =>  \Yii::t('site', 'Скачать трек "{trackName}"', ['trackName' => $model->title.' - '.$model->artist]),
                        'data-pjax' =>  'false'
                    ]
                );

                if(!\Yii::$app->user->isGuest){
                    echo Html::button(
                        FA::i($model->userSong ? 'heart' : 'heart-o'),
                        [
                            'class' => 'btn btn-secondary btn-xs toggleTrack',
                            'title' => $model->userSong ? \Yii::t('site', 'Убрать из моих треков') : \Yii::t('site', 'Добавить в мои треки')
                        ]);

                    if($model->user_id == \Yii::$app->user->identity->getId()){
                        echo Html::button(
                            FA::i('pencil'),
                            [
                                'class' => 'btn btn-secondary btn-xs editTrack',
                                'title' => \Yii::t('site', 'Редактировать')
                            ]);
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <div class="d-flex">
        <div class="waveform w-100 d-block"></div>
    </div>
</div>