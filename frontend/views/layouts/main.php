<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;

AppAsset::register($this);

$loginJs = <<<'JS'
var timer = null;

$('#loginModal').on('shown.bs.modal', function () {
  timer = setInterval(function(){
      $.ajax({
        url: '/site/is-guest'
      }).success(function(data){
          if(data === false){
              location.reload();
          }
      });
  }, 1000);
}).on('hide.bs.modal', function () {
  clearInterval(timer);
})
JS;

$css = <<<'CSS'
.navbar-inverse .dropdown-menu form .btn.btn-link:hover{
    color: #262626;
    text-decoration: none;
    background-color: #f5f5f5;
}

.navbar-inverse .dropdown-menu form .btn.btn-link:active{
    text-decoration: none;
}

.dropdown-menu > li form button.btn.btn-link{
    display: block;
    padding: 3px 20px;
    clear: both;
    font-weight: normal;
    line-height: 1.42857143;
    color: #333;
    white-space: nowrap;
    width: 100%;
    text-align: left;
}

@media (max-width: 768px) {
    .navbar-inverse .dropdown-menu > li form button.btn.btn-link{
        color: #9d9d9d;
        background-color: transparent !important;
    }
    
    .navbar-inverse .dropdown-menu > li form button.btn.btn-link:hover{
        color: #fff;
    }

}
CSS;

$this->registerCss($css);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'MuzOne',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    $username = null;

    if(!\Yii::$app->user->isGuest){
        $username = trim(Yii::$app->user->identity->username ? : Yii::$app->user->identity->first_name.' '.Yii::$app->user->identity->last_name);
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            '<li>'.
            \app\widgets\LanguagePicker::widget()
            . '</li>',
            ['label' => Yii::t('site', 'Главная'), 'url' => ['/site/index']],
            ['label' => \Yii::t('site', 'Статистика'), 'url' => ['/site/stats']],
            ['label' => Yii::t('site', 'О сервисе'), 'url' => ['/site/about']],
            Yii::$app->user->isGuest ? (
                ['label' => \Yii::t('site', 'Авторизация'), 'url' => '#', 'options' => ['data-toggle' => 'modal', 'data-target' => '#loginModal']]
            ) : (
                '<li>'.
                Html::a(
                    \Yii::t('site', 'Привет, {user}!', ['user' => $username]),
                    '#',
                    ['data-toggle' => 'dropdown', 'class' => 'dropdown-toggle']
                ).
                \yii\bootstrap\Dropdown::widget([
                    'items' => [
                        [
                            'label'  =>  \Yii::t('site', 'Мои треки'),
                            'url'   =>  '/tracks/my'
                        ],
                        '<li role="presentation" class="divider"></li>',
                        '<li>'
                        .Html::beginForm(['/site/logout'], 'post')
                        .Html::submitButton(
                            \Yii::t('site', 'Выйти'),
                            ['class' => 'btn btn-link']
                        )
                        .Html::endForm()
                        .'</li>'
                    ]
                ])
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
        <?php if(\Yii::$app->user->isGuest){
            $this->registerJs($loginJs);

            \yii\bootstrap\Modal::begin([
                'id'        =>  'loginModal',
                'header'    =>  \Yii::t('site', 'Авторизация'),
                'options'   =>  [
                    'data-static'   =>  true
                ]
            ]);

            $hash = \Yii::$app->session->get('tgAuthToken');
            $url = 'https://telegram.me/muzonebot?start='.$hash;
        ?>
            <?=\Yii::t('site', 'Для авторизации, пожалуйста, нажмите на кнопку ниже, затем нажмите START в диалоге с ботом в вашем клиенте telegram')?>
            <br>
            <br>
            <div class="text-center">
                <?=Html::a(\Yii::t('site', 'Авторизоваться через telegram'), $url, ['target' => '_blank', 'class' => 'btn btn-default'])?>
            </div>
            <br>
            <?=\Yii::t('site', 'или отправьте боту следующую команду')?>
            <br>
            <br>
            <blockquote>
                <small>/start <?=$hash?></small>
            </blockquote>
            <b><?=\Yii::t('site', 'Важно!')?></b> <?=\Yii::t('site', 'Не закрывайте это модальное окно пока бот не ответит вам что вы успешно авторизованы. В инном случае вам придётся после ответа обновить страницу самостоятельно!')?>
        <?php
            \yii\bootstrap\Modal::end();
        }else{
            \yii\bootstrap\Modal::begin([
                'id'        =>  'trackEditModal',
                'header'    =>  \Yii::t('site', 'Редактирование трека')
            ]);
            \yii\widgets\Pjax::begin([
                'enablePushState'   =>  false
            ]);
            \yii\widgets\Pjax::end();
            \yii\bootstrap\Modal::end();
        } ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Kroshyk and Lazy Penguin <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
