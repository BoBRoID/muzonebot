<?php
return [
    /*'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],*/
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'request'   =>  [
            'class' =>  'common\components\Request',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
    'timeZone'          =>  'Europe/Kiev',
    'language'          =>  'ru-RU',
    'sourceLanguage'    =>  'ru-RU',
];
