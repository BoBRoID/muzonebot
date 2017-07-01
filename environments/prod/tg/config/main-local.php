<?php


$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'urlManager'    =>  [
            'rules' =>  [
                '/hook'     =>  'site/hook',
                '/set'      =>  'site/set',
            ]
        ]
    ],
];

return $config;