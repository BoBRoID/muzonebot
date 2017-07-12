<?php

/* @var $this yii\web\View */

use rmrevin\yii\fontawesome\FA;

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
    <div class="col-md-8">
        <div class="card-group mb-0">
            <div class="card card-inverse card-info py-5 d-md-down-none" style="width:44%">
                <div class="card-block text-center">
                    <div>
                        <div class="text-center">
                            <a href="<?=$url?>" target="_blank" class="btn btn-outline-primary btn-lg active">Login <?=\rmrevin\yii\fontawesome\FA::i('telegram')?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>