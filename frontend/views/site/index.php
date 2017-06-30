<?php

/**
 * @var $this yii\web\View
 * @var $songs \yii\data\ActiveDataProvider
 * @var $searchModel \frontend\models\forms\SongSearch
 */

use yii\helpers\Html;

$this->title = \Yii::t('site', '{siteName} - муызка из мессенджера telegram', ['siteName' => \Yii::$app->params['siteName']]);
?>

<div class="row">
    <div class="col-xs-8 col-xs-offset-2">
        <div class="well">
            <h3 class="text-center"><?=\Yii::t('site', 'Поиск по базе треков')?></h3>
            <?php $form = \yii\widgets\ActiveForm::begin(['method' => 'get', 'action' => \yii\helpers\Url::to('/site/search')]) ?>
            <?=$form->field($searchModel, 'query', ['inputOptions' => ['class' => 'form-control', 'placeholder' => \Yii::t('site', 'Название трека, албьома, или исполнитель')]])->label(false)?>
            <div class="text-center">
                <button type="submit" class="btn btn-success"><?=\Yii::t('site', 'Искать')?></button>
            </div>
            <?php \yii\widgets\ActiveForm::end() ?>
        </div>
    </div>
</div>
<div class="well">
    <h3 class="text-center"><?=\Yii::t('site', '10 последних добавленных треков')?></h3>
    <?php
    \yii\widgets\Pjax::begin(['id' => 'last10tracksPjax']);
    echo \yii\widgets\ListView::widget([
        'dataProvider'  =>  $songs,
        'options'       =>  [
            'class' =>  'list-group'
        ],
        'itemOptions'   =>  ['class' => 'list-group-item'],
        'itemView'      =>  function($model) use($searchModel){
            return $this->render('../utilites/song.php', [
                'model'         =>  $model,
                'searchModel'   =>  $searchModel
            ]);
        },
        'layout'    =>  '{items}'
    ]);;
    \yii\widgets\Pjax::end();
    ?>
</div>