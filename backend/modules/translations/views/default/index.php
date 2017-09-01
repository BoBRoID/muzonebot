<?php
/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

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
                        return (int)$value === 1 ? \Spatie\Emoji\Emoji::whiteHeavyCheckMark() : \Spatie\Emoji\Emoji::crossMark();
                    },
                ],
                [
                    'class' =>  \yii\grid\ActionColumn::className()
                ]
            ]
        ])?>
    </div>
    <?php \yii\widgets\Pjax::end(); ?>
</div>
