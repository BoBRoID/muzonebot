<?php
/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $searchModel \frontend\models\forms\SongSearch
 */
use yii\helpers\Html;

?>
<div class="row">
    <div class="col-xs-8 col-xs-offset-2">
        <div class="well">
            <h3 class="text-center"><?=\Yii::t('site', 'Поиск по базе треков')?></h3>
            <?php $form = \yii\bootstrap\ActiveForm::begin(['layout' => 'horizontal', 'method' => 'get', 'action' => \yii\helpers\Url::to('/site/search')]) ?>
            <?=$form->field($searchModel, 'query', ['inputOptions' => ['class' => 'form-control', 'placeholder' => \Yii::t('site', 'Название трека, албьома, или исполнитель')]])->label(false)?>
            <h4 class="text-center"><?=\Yii::t('site', 'Фильтровать найденые треки по')?></h4>
            <?=$form->field($searchModel, 'trackName')->label(\Yii::t('site', 'Названию'))?>
            <?=$form->field($searchModel, 'artist')->label(\Yii::t('site', 'Исполнителю'))?>
            <div class="text-center">
                <button type="submit" class="btn btn-success"><?=\Yii::t('site', 'Искать')?></button>
            </div>
            <?php \yii\bootstrap\ActiveForm::end() ?>
        </div>
    </div>
</div>
<div class="well">
    <?php
    \yii\widgets\Pjax::begin();
    echo \yii\widgets\ListView::widget([
        'dataProvider'  =>  $dataProvider,
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
        'layout'    =>  '{items} '.\yii\helpers\Html::tag('div', '{pager}', ['class' => 'text-center'])
    ]);
    \yii\widgets\Pjax::end()?>
</div>