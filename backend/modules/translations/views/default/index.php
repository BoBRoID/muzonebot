<?php
/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

use rmrevin\yii\fontawesome\FA;
use Spatie\Emoji\Emoji;
use yii\helpers\Html;

$this->title = \Yii::t('manage', 'Список языков');
$this->params['breadcrumbs'][] = [
    'url'   =>  '/translations/default/index',
    'label' =>  \Yii::t('manage', 'Переводы')
];
$this->params['breadcrumbs'][] = $this->title;
?>
<h4><?=$this->title?></h4>


<div class="card">
    <?php \yii\widgets\Pjax::begin([
        'id'    =>  'languages-grid'
    ]); ?>
    <div class="table-responsive">
        <?=\yii\grid\GridView::widget([
            'dataProvider'  =>  $dataProvider,
            'tableOptions'  =>  [
                'class' =>  'table table-condensed table-striped',
            ],
            'summary'   =>  \Yii::t('manage', 'Отображаются результаты с <b>{begin, number} по {end, number}</b>. Всего найдено <b>{totalCount, number}</b> {totalCount, plural, one{язык} other{языка}}.'),
            'layout'    =>  "<div class=\"card-header\">{summary}</div><div class=\"card-block p-0\">\n{items}\n<div class=\"text-center\">{pager}</div></div>",
            'columns'   =>  [
                'name',
                [
                    'attribute' =>  'flag',
                    'value'     =>  function($model, $value){
                        return json_decode('"'.$value.'"');
                    }
                ],
                [
                    'attribute' =>  'default',
                    'value'     =>  function($model, $value){
                        return (int)$value === 1 ? Emoji::whiteHeavyCheckMark() : Emoji::crossMark();
                    },
                ],
                [
                    'class'     =>  \yii\grid\ActionColumn::className(),
                    'buttons'   =>  [
                        'edit'          =>  function($key, $model){
                            return Html::a(FA::i('pencil'), ['/translations/default/edit', 'id' => $model->language_id], ['class' => 'btn btn-secondary btn-sm']);
                        },
                        'translations'  =>  function($key, $model){
                            return Html::a(FA::i('list'), ['/translations/messages/index', 'id' => $model->language_id], ['class' => 'btn btn-secondary btn-sm']);
                        },
                        'setDefault'    =>  function($key, $model){

                        }
                    ],
                    'template'  =>  Html::tag('div', '{edit} {translations} {setDefault}', ['class' => 'btn-group btn-group-sm'])
                ]
            ]
        ])?>
    </div>
    <?php \yii\widgets\Pjax::end(); ?>
</div>
