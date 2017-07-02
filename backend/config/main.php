<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules'   =>  [
        'translatemanager' => [
            'class' => 'lajax\translatemanager\Module',
            'allowedIPs' => ['*'],
            'roles' => ['@'],
        ],
        'rbac' => [
            'class'     =>  'dektrium\rbac\RbacWebModule',
            'admins'    =>  ['SomeWho']
        ],
    ],
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
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'idParam'           =>  'adminTgAuthToken',
            'identityClass' => 'backend\models\User',
            'enableAutoLogin' => true,
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'tgmuzone-backend',
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
        ],
    ],
    'params' => $params,
];
