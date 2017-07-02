<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'request'   =>  [
            'class' =>  'common\components\Request',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
