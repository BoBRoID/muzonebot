<?php

/* @var $this \yii\web\View */
/* @var $content string */

use common\widgets\Modal;
use frontend\widgets\NavBar;
use yii\bootstrap\Dropdown;
use yii\helpers\Html;
use yii\bootstrap\Nav;
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
button.btn-link.dropdown-item{
    cursor: pointer;
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
    'brandLabel'        => 'MuzOne',
    'brandUrl'          => Yii::$app->homeUrl,
    'options'           => [
        'class'             =>  'navbar-light navbar-toggleable-sm bd-navbar bg-faded',
        'tag'               =>  'header'
    ],
    'containerOptions'  =>  [
        'class'             =>  'collapse navbar-collapse justify-content-end',
        'aria-expanded'     =>  false
    ],
    'innerContainerOptions'  =>  [
        'class'             =>  'container',
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
    '<li class="nav-item">'.
    \frontend\widgets\LanguagePicker::widget()
    . '</li>',
    ['label' => Yii::t('site', 'Главная'), 'url' => ['/site/index']],
    ['label' => \Yii::t('site', 'Статистика'), 'url' => ['/site/stats']],
    ['label' => Yii::t('site', 'О сервисе'), 'url' => ['/site/about']]
];

$items[] = Yii::$app->user->isGuest ?
    ['label' => \Yii::t('site', 'Авторизация'), 'url' => '#', 'options' => ['data-toggle' => 'modal', 'data-target' => '#loginModal']] :
    '<li class="nav-item dropdown">'.
    Html::a(
        \Yii::t('site', 'Привет, {user}!', ['user' => $username]),
        '#',
        ['data-toggle' => 'dropdown', 'class' => 'nav-link dropdown-toggle', 'id' => 'navbarUserMenu']
    ).
    Dropdown::widget([
        'items' => [
            [
                'label'  =>  \Yii::t('site', 'Мои треки'),
                'url'   =>  '/tracks/my'
            ],
            '<div role="presentation" class="divider"></div>',
            '<div>'
            .Html::beginForm(['/site/logout'])
            .Html::submitButton(
                \Yii::t('site', 'Выйти'),
                ['class' => 'btn btn-link dropdown-item']
            )
            .Html::endForm()
            .'</div>'
        ]
    ])
    . '</li>';

echo Nav::widget([
    'options'   => ['class' => 'navbar navbar-nav bg-faded'],
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
