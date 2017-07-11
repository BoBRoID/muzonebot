<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\bootstrap\Modal;
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
          url: '/site/is-guest',
          method: 'post'
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

$this->registerJsFile(\yii\helpers\Url::to(['/site/get-routes']));

?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?php
NavBar::begin([
    'brandLabel' => 'MuzOne',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-light navbar-toggleable navbar-fixed-top bg-faded',
    ],
    'containerOptions' =>  [
        'class' =>  'collapse navbar-collapse d-flex justify-content-end'
    ]
]);

$username = null;

if(!\Yii::$app->user->isGuest){
    /**
     * @var $user \frontend\models\User
     */
    $user = \Yii::$app->user->identity;
    $username = trim($user->username ? : $user->first_name.' '.$user->last_name);
}

$items = [
    '<li>'.
    \app\widgets\LanguagePicker::widget()
    . '</li>',
    ['label' => Yii::t('site', 'Главная'), 'url' => ['/site/index']],
    ['label' => \Yii::t('site', 'Статистика'), 'url' => ['/site/stats']],
    ['label' => Yii::t('site', 'О сервисе'), 'url' => ['/site/about']]
];

$items[] = Yii::$app->user->isGuest ?
    ['label' => \Yii::t('site', 'Авторизация'), 'url' => '#', 'options' => ['data-toggle' => 'modal', 'data-target' => '#loginModal']] :
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
            .Html::beginForm(['/site/logout'])
            .Html::submitButton(
                \Yii::t('site', 'Выйти'),
                ['class' => 'btn btn-link']
            )
            .Html::endForm()
            .'</li>'
        ]
    ])
    . '</li>';

echo Nav::widget([
    'options'   => ['class' => 'navbar navbar-toggleable-md navbar-light bg-faded'],
    'items'     => $items,
]);
NavBar::end();
?>
<div class="wrap">
    <div class="container pt-3">
        <?= Breadcrumbs::widget([
            'links'                 =>  isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'itemTemplate'          =>  "<li class=\"breadcrumb-item\">{link}</li>\n",
            'activeItemTemplate'    =>  "<li class=\"breadcrumb-item active\">{link}</li>\n"
        ]) ?>
        <?= $content ?>
        <?php if(\Yii::$app->user->isGuest){
            $this->registerJs($loginJs);

            Modal::begin([
                'id'        =>  'loginModal',
                'header'    =>  \Yii::t('site', 'Авторизация'),
                'size'      =>  Modal::SIZE_LARGE,
                'options'   =>  [
                    'data-static'   =>  1
                ]
            ]);

            echo $this->render('../utilites/login.php'),
            Html::tag('b', \Yii::t('site', 'Важно!')),
            \Yii::t('site', 'Не закрывайте это модальное окно пока бот не ответит вам что вы успешно авторизованы. В инном случае вам придётся после ответа обновить страницу самостоятельно!');

            Modal::end();
        }else{
            Modal::begin([
                'id'        =>  'trackEditModal',
                'header'    =>  \Yii::t('site', 'Редактирование трека')
            ]);
            \yii\widgets\Pjax::begin([
                'enablePushState'   =>  false
            ]);
            \yii\widgets\Pjax::end();
            Modal::end();
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
