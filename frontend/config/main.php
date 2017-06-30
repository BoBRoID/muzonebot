<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'Muz',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
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
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'idParam'           =>  'tgAuthToken',
            'identityClass'     =>  'frontend\models\User',
            'enableAutoLogin'   =>  true,
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
            'class' => 'codemix\localeurls\UrlManager',
            'languages' => [
                'russian'       => 'ru-RU',
                'english_us'    => 'en-US',
                'ukrainian'     => 'uk-UA',
                'portuguese_br' => 'pt-BR'
            ],
            'enablePrettyUrl'   =>  true,
            'showScriptName'    =>  false,
        ],
    ],
    'params' => $params,
];
