<?php
/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $searchModel \backend\models\SongSearch
 */
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\Html;
use yii\helpers\Url;

$js = <<<'JS'
    $(document).on('click', '.removeTrack', function(){
        var row = $(this).closest('[data-key]'),
            id = row.data('key');
        
        $(this).prop('disabled', true);
        
        $.ajax('/track/remove?id=' + id, {
            method: 'POST'
        }).success(function(){
            row.remove();
        });
    });
    
    $("#songsearch-addedfrom").on("dp.change", function (e) {
        console.log('adfdsf');
        $('#songsearch-addedto').data("DateTimePicker").minDate(e.date);
    });
    $("#songsearch-addedto").on("dp.change", function (e) {
        console.log('adf');
        $('#songsearch-addedfrom').data("DateTimePicker").maxDate(e.date);
    });
JS;

$this->registerJs($js);

$css = <<<'CSS'
.table tr td a{
    white-space: pre-wrap; /* css-3 */    
    white-space: -moz-pre-wrap; /* Mozilla, since 1999 */
    white-space: -pre-wrap; /* Opera 4-6 */    
    white-space: -o-pre-wrap; /* Opera 7 */    
    word-wrap: break-word; /* Internet Explorer 5.5+ */
    word-wrap: break-word;
}
CSS;

$this->registerCss($css);

$this->title = \Yii::t('manage', 'Список добавленых треков');

$this->params['breadcrumbs'][] = ['url' => Url::to(['/site/index']), 'label' => \Yii::t('manage', 'Управление системой')];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?=$this->title?></h1>
<hr>
<div class="well">
    Фильтры
    <?php $form = \yii\bootstrap\ActiveForm::begin(['method' => 'get', 'layout' => 'horizontal']); ?>
    <?=$form->field($searchModel, 'query')?>
    <?=$form->field($searchModel, 'artist')?>
    <?=$form->field($searchModel, 'trackName')?>
    <div class="text-center"><button type="submit" class="btn btn-success"><?=\Yii::t('manage', 'Фильтровать список треков')?></button></div>
    <?php $form->end(); ?>
</div>
<?=\yii\grid\GridView::widget([
    'dataProvider'  =>  $dataProvider,
    'tableOptions'  =>  [
        'class' =>  'table table-condensed table-striped'
    ],
    'pager'     =>  [
        'options'           =>  [
            'class' =>  'pagination mx-auto mt-3 d-inline-flex'
        ],
        'linkOptions'       =>  [
            'class'             =>  'page-link',
        ],
        'pageCssClass'      =>  'page-item',
        'prevPageCssClass'  =>  'page-item',
        'nextPageCssClass'  =>  'page-item',
        'disabledListItemSubTagOptions' =>  [
            'class' =>  'page-link'
        ]
    ],
    'columns'       =>  [
        [
            'attribute' =>  'id'
        ],
        [
            'label'     =>  \Yii::t('manage', 'Название '),
            'attribute' =>  'title',
            'format'    =>  'html',
            'value'     =>  function($model) use($searchModel){
                /**
                 * @var $model \common\models\Song
                 */
                return Html::a($model->title, Url::current([
                    $searchModel->formName() => ['trackName' => $model->title]
                ]), ['title' => \Yii::t('site', 'Искать треки с названием {name}', ['name' => $model->title])]);
            }
        ],
        [
            'label'     =>  \Yii::t('manage', 'Исполнитель'),
            'attribute' =>  'artist',
            'format'    =>  'html',
            'value'     =>  function($model) use($searchModel){
                /**
                 * @var $model \common\models\Song
                 */
                return Html::a($model->artist, Url::current([
                    $searchModel->formName() => ['artist' => $model->artist]
                ]), ['title' => \Yii::t('site', 'Искать треки исполнителя {name}', ['name' => $model->artist])]);
            }
        ],
        [
            'label'     =>  \Yii::t('manage', 'Длина'),
            'attribute' =>  'duration',
            'contentOptions'    =>  [
                'class' =>  'text-center'
            ],
            'value'     =>  function($model){
                $minutes = floor($model->duration / 60);
                $seconds = $model->duration - ($minutes * 60);

                if($seconds <= 9){
                    $seconds = '0'.$seconds;
                }

                return $minutes.':'.$seconds;
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
                return Html::a($model->user->username ? : $model->user->first_name.' '.$model->user->last_name, Url::current([$searchModel->formName() => ['userId' => $model->user_id]]));
            }
        ],
        [
            'label'     =>  \Yii::t('manage', 'Добавлено'),
            'attribute' =>  'added',
            'format'    =>  'html',
            'contentOptions'    =>  [
                'class' =>  'text-center'
            ],
            'value'     =>  function($model){
                return \Yii::$app->formatter->asDatetime($model->added, 'MM/dd/yyyy').'&nbsp;'.Html::tag('br', null, ['class' => 'hidden-md hidden-lg']).\Yii::$app->formatter->asDatetime($model->added, 'HH:mm');
            }
        ],
        [
            'class'     =>  \yii\grid\ActionColumn::className(),
            'buttons'   =>  [
                'edit'  =>  function($url, $model){
                    return Html::a(FA::i('pencil'), Url::to(['track/edit', 'id' => $model->id]), ['class' => 'btn btn-secondary']);
                },
                'play'  =>  function($model){
                    return Html::button(FA::i('play'), ['class' => 'btn btn-secondary pull-left']);
                },
                'download'  =>  function($url, $model){
                    return Html::a(FA::i('save'), ['/site/get-track-link', 'id' => $model->id], ['class' => 'btn btn-secondary']);
                },
                'remove'  =>  function($model){
                    return Html::button(FA::i('trash'), ['class' => 'btn btn-secondary removeTrack']);
                }
            ],
            'template'  =>  Html::tag('div', Html::tag('div', '{play}{download}', ['class' => 'btn-group btn-group-sm pull-left']).Html::tag('div', '{remove}{edit}', ['class' => 'btn-group btn-group-sm pull-right']), ['style' => 'width: 150px'])
        ]
    ]
])?>