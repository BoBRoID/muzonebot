<?php

/**
 * @var $this yii\web\View
 * @var $songs \yii\data\ActiveDataProvider
 * @var $searchModel \frontend\models\forms\SongSearch
 */

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$this->title = \Yii::t('site', '{siteName} - муызка из мессенджера telegram', ['siteName' => \Yii::$app->params['siteName']]);
?>

<div class="jumbotron jumbotron-fluid">
    <div class="container">
        <h1 class="display-3 pb-2"><?=\Yii::t('site', 'Поиск по базе треков')?></h1>
        <div class="lead">
            <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to('/site/search')]) ?>
            <?=$form->field($searchModel, 'query', ['inputOptions' => ['class' => 'form-control form-control-lg text-center', 'placeholder' => \Yii::t('site', 'Название трека, албьома, или исполнитель')]])->label(false)?>
            <div class="text-center">
                <button type="submit" class="btn btn-success"><?=\Yii::t('site', 'Искать')?></button>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>
<h3 class="text-center"><?=\Yii::t('site', '10 последних добавленных треков')?></h3>
<?php
Pjax::begin(['id' => 'last10tracksPjax']);
echo ListView::widget([
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
]);
Pjax::end();
?>