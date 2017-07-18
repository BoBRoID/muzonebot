<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use backend\widgets\NavBar;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
$this->registerJsFile(\yii\helpers\Url::to(['/site/get-routes']));

AppAsset::register($this);

$username = null;

if(!\Yii::$app->user->isGuest){
    /**
     * @var $user \frontend\models\User
     */
    $user = \Yii::$app->user->identity;
    $username = trim($user->username ? : $user->first_name.' '.$user->last_name);
}
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
    <body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
    <?php $this->beginBody();

    NavBar::begin([
        'brandLabel'        => 'adm.MuzOne',
        'brandUrl'          => Yii::$app->homeUrl,
        'options'           => [
            'class'             =>  'app-header navbar',
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
    echo Nav::widget([
        'options'   =>  [
            'class' =>  'nav navbar-nav d-md-down-none',
        ],
        'items' => [
            [
                'linkOptions'   =>  [
                    'class'     =>  'nav-link navbar-toggler sidebar-toggler',
                ],
                'label'     =>  '☰',
                'url'       =>  '#'
            ],
            [
                'options'   =>  [
                    'class'     =>  'nav-item px-3',
                ],
                'label' =>  \Yii::t('manage', 'Сайт'),
                'url'   =>  Url::to(\Yii::$app->params['frontend_url'])
            ],
        ],
    ]);
    echo Nav::widget([
        'encodeLabels'  =>  false,
        'options'   =>  [
            'class' =>  'nav navbar-nav ml-auto',
        ],
        'items' => [
            [
                'label' =>  \Yii::$app->user->identity->username,
                'url'   =>  '#',
                'dropDownOptions'   =>  [
                    'class' =>  'dropdown-menu dropdown-menu-right'
                ],
                'items' =>  [
                    Html::tag('div', Html::tag('strong', \Yii::t('manage', 'Аккаунт')), ['class' => 'dropdown-header text-center']),
                    '<div>'
                    .Html::beginForm(['/site/logout'])
                    .Html::submitButton(
                        FA::i('lock').\Yii::t('manage', 'Выйти'),
                        ['class' => 'btn btn-link dropdown-item']
                    )
                    .Html::endForm()
                    .'</div>'
                ],
            ],
            [
                'label'     =>  '&nbsp;',
                'options'   =>  [
                    'class'     =>  'nav-item d-md-down-none'
                ],
                'url'   =>  '#'
            ]
        ],
    ]);
    NavBar::end();
    ?>
        <div class="app-body">
            <div class="sidebar">
                <nav class="sidebar-nav">
                    <?=Nav::widget([
                        'encodeLabels'  =>  false,
                        'options'   =>  [
                            'class' =>  'nav navbar-nav ml-auto',
                        ],
                        'items' => [
                            [
                                'label'     =>  Html::tag('i', null, ['class' => 'icon-direction']).\Yii::t('manage', 'Главная'),
                                'url'       =>  Url::to(['/site/index'])
                            ],
                            [
                                'label'     =>  Html::tag('i', null, ['class' => 'icon-people']).\Yii::t('manage', 'Пользователи'),
                                'url'       =>  Url::to(['/site/users'])
                            ],
                            [
                                'label'     =>  Html::tag('i', null, ['class' => 'icon-tag']).\Yii::t('manage', 'Жанры'),
                                'url'       =>  Url::to(['/site/genres'])
                            ],
                            [
                                'label'     =>  Html::tag('i', null, ['class' => 'icon-music-tone-alt']).\Yii::t('manage', 'Треки'),
                                'url'       =>  Url::to(['/track/index'])
                            ],
                            [
                                'label'     =>  Html::tag('i', null, ['class' => 'icon-grid']).\Yii::t('manage', 'Альбомы'),
                                'url'       =>  Url::to(['/site/albums'])
                            ],
                            [
                                'label'     =>  Html::tag('i', null, ['class' => 'icon-bubble']).\Yii::t('manage', 'Отзывы'),
                                'url'       =>  Url::to(['/site/feedback'])
                            ],
                            [
                                'label'     =>  Html::tag('i', null, ['class' => 'icon-flag']).\Yii::t('manage', 'Переводы'),
                                'url'       =>  Url::to(['/translatemanager/language/list'])
                            ],
                            '<li class="nav-title">'.\Yii::t('manage', 'Сервисные штуки').'</li>',
                            [
                                'label'     =>  Html::tag('i', null, ['class' => 'icon-magnifier']).\Yii::t('manage', 'Распознавалка тэгов'),
                                'url'       =>  Url::to(['/utilities/get-tags'])
                            ],
                        ],
                    ])?>
                </nav>
            </div>

            <main class="main">
                <?= Breadcrumbs::widget([
                    'links'                 =>  isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    'itemTemplate'          =>  "<li class=\"breadcrumb-item\">{link}</li>\n",
                    'activeItemTemplate'    =>  "<li class=\"breadcrumb-item active\">{link}</li>\n"
                ]) ?>
                <div class="container-fluid">
                    <div class="animated fadeIn">
                        <?= Alert::widget() ?>
                        <?=$content?>
                    </div>
                </div>
            </main>
            <?=isset($this->params['afterContent']) ? implode('', $this->params['afterContent']) : null?>
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
