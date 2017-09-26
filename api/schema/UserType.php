<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 26.09.17
 * Time: 15:24
 */

namespace api\schema;


use api\helpers\MainHelper;
use api\models\TrackSearch;
use api\types\Types;
use common\models\User;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class UserType extends ObjectType
{

    public function __construct()
    {
        $config = [
            'fields'    =>  function(){
                return [
                    'id'    =>  [
                        'type'          =>  Type::id(),
                        'description'   =>  \Yii::t('api', 'ID')
                    ],
                    'first_name'    =>  [
                        'type'          =>  Type::string(),
                        'description'   =>  \Yii::t('api', 'Имя')
                    ],
                    'last_name'     =>  [
                        'type'          =>  Type::string(),
                        'description'   =>  \Yii::t('api', 'Фамилия')
                    ],
                    'username'      =>  [
                        'type'          =>  Type::string(),
                        'description'   =>  \Yii::t('api', 'Логин')
                    ],
                    'created_at'    =>  [
                        'type'          =>  Type::int(),
                        'description'   =>  \Yii::t('api', 'Дата создания'),
                        'args'          =>  [
                            'format'        =>  Type::string()
                        ],
                        'resolve'       =>  function(User $user, $args){
                            $created = $user->created_at;

                            if(!array_key_exists('format', $args)){
                                return $created;
                            }

                            return MainHelper::formatTimestampByType($created, $args['format']);

                        }
                    ],
                    'updated_at'    =>  [
                        'type'          =>  Type::int(),
                        'description'   =>  \Yii::t('api', 'Дата обновления'),
                        'args'          =>  [
                            'format'        =>  Type::string()
                        ],
                        'resolve'       =>  function(User $user, $args){
                            $created = $user->updated_at;

                            if(!array_key_exists('format', $args)){
                                return $created;
                            }

                            return MainHelper::formatTimestampByType($created, $args['format']);

                        }
                    ],
                    'language_code' =>  [
                        'type'          =>  Type::string(),
                        'description'   =>  \Yii::t('api', 'Двухбуквенный код языка')
                    ],
                    'language_id'   =>  [
                        'type'          =>  Type::string(),
                        'description'   =>  \Yii::t('api', 'Пятибуквенный идентификатор языка')
                    ],
                    'tracks'        =>  [
                        'type'          =>  Type::listOf(Types::track()),
                        'description'   =>  \Yii::t('api', 'Список треков пользователя'),
                        'args'          =>  [
                            'title' =>  Type::string(),
                            'artist'=>  Type::string(),
                            'query' =>  Type::string()
                        ],
                        'resolve'   =>  function(User $user, $args){
                            return (new TrackSearch(array_merge($args, ['userId' => $user->id])))->getResults()->all();
                        }
                    ]
                ];
            }
        ];

        parent::__construct($config);
    }

}