<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id'        => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'components' => [
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
                '*' => [
                    'class'                 =>  'yii\i18n\DbMessageSource',
                    'sourceMessageTable'    =>  '{{%language_source}}',
                    'sourceLanguage'        =>  'ru-RU',
                    'messageTable'          =>  '{{%language_translate}}',
                    'enableCaching'         =>  true,
                    'cachingDuration'       =>  10,
                    'forceTranslation'      =>  true,
                ],
            ],
        ],
        'user' => [
            'identityClass'     =>  false,
        ],
        'request' => [
            'csrfParam' => '_csrf-api',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules'         =>  [
                'graphQL'   =>  'graphql/index',
                ''          =>  'site/index'
            ],
        ],
    ],
    'params'    => $params,
];
