<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 29.06.2017
 * Time: 13:26
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $searchModel \frontend\models\forms\SongSearch
 */

$this->title = \Yii::t('site', 'Мои треки');
$this->params['breadcrumbs'][] = $this->title;

echo \yii\helpers\Html::tag('h1', $this->title);

echo \yii\widgets\ListView::widget([
    'dataProvider'  =>  $dataProvider,
    'options'       =>  [
        'class' =>  'list-group'
    ],
    'itemOptions'   =>  ['class' => 'list-group-item'],
    'itemView'      =>  function($model) use($searchModel){
        return $this->render('../utilites/song.php', [
            'model'         =>  $model,
            'searchModel'   =>  $searchModel,
            'searchLink'    =>  '/tracks/my'
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
    'layout'    =>  '{items} '.\yii\helpers\Html::tag('div', '{pager}', ['class' => 'text-center'])
]);