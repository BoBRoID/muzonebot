<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 29.06.2017
 * Time: 13:26
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $searchModel \frontend\models\forms\SongSearch
 */

use nirvana\infinitescroll\InfiniteScrollPager;
use yii\helpers\Html;

$this->title = \Yii::t('site', 'Мои треки');
$this->params['breadcrumbs'][] = $this->title;

echo Html::tag('h1', $this->title);
echo \yii\widgets\ListView::widget([
    'dataProvider'  =>  $dataProvider,
    'options'       =>  [
        'class' =>  'list-group',
    ],
    'id'    =>  'my-tracks-list',
    'itemOptions'   =>  ['class' => 'list-group-item'],
    'itemView'      =>  function($model) use($searchModel){
        return $this->render('../utilites/song.php', [
            'model'         =>  $model,
            'searchModel'   =>  $searchModel,
            'searchLink'    =>  '/tracks/my'
        ]);
    },
    'pager'     =>  [
        'class'         =>  InfiniteScrollPager::className(),
        'itemsCssClass' =>  'list-group-content',
        'widgetId'      =>  'my-tracks-list',
        'linkOptions'   =>  [
            'class' =>  'page-link',
        ],
        'pluginOptions' => [
            'history'   =>  false,
            'button'    =>  false,
            'loading' => [
                'msgText'       =>  Html::tag('div', \Yii::t('site', 'Загружается следующая страница. Пожалуйста, подождите'), ['class' => 'text-center']),
                'finishedMsg'   =>  Html::tag('div', \Yii::t('site', 'Вы достигли конца'), ['class' => 'text-center']),
            ],
        ],
    ],
    'layout'    =>  Html::tag('div', '{items}', ['class' => 'list-group-content']).' '. Html::tag('div', '{pager}', ['class' => 'text-center pagination-wrap']),
]);