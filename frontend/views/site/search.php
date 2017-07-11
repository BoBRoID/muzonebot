<?php
/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $searchModel \frontend\models\forms\SongSearch
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = \Yii::t('site', 'Поиск');
?>
<div class="row">
    <div class="col-8 offset-2">
        <div class="jumbotron jumbotron-fluid mb-3 pb-3">
            <div class="container">
                <h1 class="display-3 pb-2"><?=\Yii::t('site', 'Поиск по базе треков')?></h1>
                <div class="lead">
                    <?php $form = ActiveForm::begin(['layout' => 'horizontal', 'method' => 'get', 'action' => \yii\helpers\Url::to('/site/search')]) ?>
                    <?=$form->field($searchModel, 'query', [
                        'wrapperOptions'  => [
                            'class' =>  'col-12'
                        ],
                        'inputOptions'      => [
                            'class' => 'form-control form-control-lg text-center',
                            'placeholder' => \Yii::t('site', 'Название трека, албьома, или исполнитель')
                        ]
                    ])->label(false)?>

                    <div id="accordion" role="tablist" class="mb-3" aria-multiselectable="true">
                        <div class="card">
                            <div class="card-header" role="tab" id="filtersHeading">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#filters" aria-expanded="true" aria-controls="filters">
                                        <?=\Yii::t('site', 'Фильтровать найденые треки по')?>
                                    </a>
                                </h5>
                            </div>

                            <div id="filters" class="collapse" role="tabpanel" aria-labelledby="filtersHeading">
                                <div class="card-block">
                                    <div class="col-10 offset-1">
                                        <?=$form->field($searchModel, 'trackName', [
                                            'wrapperOptions'  => [
                                                'class' =>  'col-9'
                                            ]
                                        ])->label(\Yii::t('site', 'Названию'))?>
                                        <?=$form->field($searchModel, 'artist', [
                                            'wrapperOptions'  => [
                                                'class' =>  'col-9'
                                            ]
                                        ])->label(\Yii::t('site', 'Исполнителю'))?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success"><?=\Yii::t('site', 'Искать')?></button>
                    </div>
                    <?php $form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
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
    'layout'    =>  '{items} '. Html::tag('div', '{pager}', ['class' => 'text-center'])
]);
\yii\widgets\Pjax::end()?>