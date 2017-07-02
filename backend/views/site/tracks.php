<?php
/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $searchModel \backend\modules\manage\models\SongSearch
 */
use yii\helpers\Html;

$this->title = \Yii::t('manage', 'Список добавленых треков');

$this->params['breadcrumbs'][] = ['url' => \yii\helpers\Url::to(['default/index']), 'label' => \Yii::t('manage', 'Управление системой')];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?=$this->title?></h1>
<hr>
<div class="well">
    Фильтры
    <?php $form = \yii\bootstrap\ActiveForm::begin(['method' => 'get', 'layout' => 'horizontal']); ?>
    <?=$form->field($searchModel, 'query')?>
    <?=$form->field($searchModel, 'userId')->dropDownList($searchModel->getUsers())?>
    <?=$form->field($searchModel, 'artist')?>
    <?=$form->field($searchModel, 'trackName')?>
    <div class="text-center"><button type="submit" class="btn btn-success"><?=\Yii::t('manage', 'Фильтровать список треков')?></button></div>
    <?php $form->end(); ?>
</div>
<?=\yii\grid\GridView::widget([
    'dataProvider'  =>  $dataProvider,
    'columns'       =>  [
        [
            'attribute' =>  'id'
        ],
        [
            'label'     =>  \Yii::t('manage', 'Название и исполнитель'),
            'attribute' =>  'title',
            'format'    =>  'html',
            'value'     =>  function($model) use($searchModel){
                /**
                 * @var $model \common\models\Song
                 */
                return Html::a($model->title, [$searchModel->formName() => ['trackName' => $model->title]], ['title' => \Yii::t('site', 'Искать треки с названием {name}', ['name' => $model->title])]).
                    ' - '.
                    Html::a($model->artist, [$searchModel->formName() => ['artist' => $model->artist]], ['title' => \Yii::t('site', 'Искать треки исполнителя {name}', ['name' => $model->artist])]);
            }
        ],
        [
            'label'     =>  \Yii::t('manage', 'Добавил'),
            'format'    =>  'html',
            'attribute' =>  'user_id',
            'value'     =>  function($model, $value) use ($searchModel){
                if(!$model->user){
                    return null;
                }
                /**
                 * @var $model \common\models\Song
                 */
                return Html::a($model->user->username ? : $model->user->first_name.' '.$model->user->last_name, [$searchModel->formName() => ['userId' => $model->user_id]]);
            }
        ],
        [
            'label'     =>  \Yii::t('manage', 'Добавлено'),
            'attribute' =>  'added',
            'value'     =>  function($model){
                return \Yii::$app->formatter->asDatetime($model->added);
            }
        ],
        [
            'class'     =>  \yii\grid\ActionColumn::className(),
            'buttons'   =>  [
                'edit'  =>  function($url, $model){
                    return Html::a(Html::tag('i', null, ['class' => 'glyphicon glyphicon-pencil']), \yii\helpers\Url::to(['track/edit', 'id' => $model->id]), ['class' => 'btn btn-default']);
                },
                'play'  =>  function($model){
                    return Html::button(Html::tag('i', null, ['class' => 'glyphicon glyphicon-play']), ['class' => 'btn btn-default pull-left']);
                },
                'download'  =>  function($url, $model){
                    return Html::a(Html::tag('i', null, ['class' => 'glyphicon glyphicon-floppy-save']), ['/site/get-track-link', 'id' => $model->id], ['class' => 'btn btn-default']);
                },
                'remove'  =>  function($model){
                    return Html::button(Html::tag('i', null, ['class' => 'glyphicon glyphicon-trash']), ['class' => 'btn btn-default']);
                }
            ],
            'template'  =>  Html::tag('div', '{play}{download}', ['class' => 'btn-group pull-left']).Html::tag('div', '{remove}{edit}', ['class' => 'btn-group pull-right'])
        ]
    ]
])?>