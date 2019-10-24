<?php

/* @var $this yii\web\View */

use common\widgets\Modal;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
$hash = \Yii::$app->session->get('adminTgAuthToken');
$url = 'https://telegram.me/muzonebot?start='.$hash.'A1F0';

$js = <<<'JS'
setInterval(function(){
      $.ajax({
        url: '/site/is-guest'
      }).success(function(data){
          if(data === false){
              location.reload();
          }
      });
  }, 5000);
JS;

$this->registerJs($js);

?>
<div class="row justify-content-center">
    <div class="btn-group btn-group-lg">
        <?= Html::a(\Yii::t('manage', 'Login {icon}', ['icon' => FA::i('telegram')]), $url, ['target' => '_blank', 'class' => 'btn btn-outline-primary btn-lg px-5 py-3'])?>

        <div class="btn-group" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
            <?=\yii\bootstrap\Dropdown::widget([
                'options'           =>  [
                    'aria-labeledby'    =>  'btnGroupDrop1',
                    'class'             =>  'dropdown-menu',
                ],
                'items'             =>  [
                    [
                        'url'       =>  '#helpModal',
                        'linkOptions'   =>  [
                            'data-toggle' => 'modal',
                            'data-target' => '#helpModal'
                        ],
                        'label'     =>  \Yii::t('manage', 'Need help?')
                    ]
                ]
            ])?>
        </div>
    </div>
</div>

<?php
Modal::begin([
    'id'        =>  'helpModal',
    'header'    =>  \Yii::t('site', 'Авторизация'),
    'size'      =>  Modal::SIZE_DEFAULT,
    'options'   =>  [
        'data-static'   =>  1
    ]
]);

echo Html::tag('p', \Yii::t('manage', 'You know what need to do'));
echo Html::tag('blockquote', '/start '.$hash.'A1F0', ['class' => 'blockquote']);

Modal::end();