<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 26.09.17
 * Time: 15:10
 */

namespace api\types;

use api\models\TrackSearch;
use common\models\Song;
use common\models\User;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use yii\db\Expression;
use yii\helpers\Url;

class QueryType extends ObjectType
{

    public function __construct()
    {
        $config = [
            'fields'    =>  function(){
                return [
                    'user'  =>  [
                        'type'  =>  Types::user(),
                        'args'  =>  [
                            'id'    =>  Type::nonNull(Type::int()),
                        ],
                        'resolve'   =>  function($root, $args){
                            return User::findOne(['id' => $args['id']]);
                        }
                    ],
                    'track' => [
                        'type' => Types::track(),
                        'args' => [
                            'id' => Type::nonNull(Type::int())
                        ],
                        'resolve' => function ($root, $args) {
                            return Song::findOne(['id' => $args['id']]);
                        }
                    ],
                    'tracks'    =>  [
                        'type'  =>  Type::listOf(Types::track()),
                        'args'  =>  [
                            'title'     =>  Type::string(),
                            'artist'    =>  Type::string(),
                            'query'     =>  Type::string(),
                            'offset'    =>  Type::int(),
                            'limit'     =>  Type::int(),
                            'order'     =>  Type::string()
                        ],
                        'resolve'   =>  function($root, $args){
                            $query = new TrackSearch();
                            $query->setAttributes($args);
                            $query = $query->getResults();

                            if(array_key_exists('limit', $args)){
                                $query->limit($args['limit']);
                            }

                            if(array_key_exists('offset', $args)){
                                $query->offset($args['offset']);
                            }

                            if(array_key_exists('order', $args)){
                                $query->addOrderBy($args['order']);
                            }

                            return $query->all();
                        }
                    ],
                    'lastListenedTrack'     =>  [
                        'type'      =>  Types::track(),
                        'resolve'   =>  function($root, $args) {
                            return Song::find()->orderBy(new Expression('RAND()'))->one();
                        }
                    ],
                    'userTracks'    =>  [
                        'type'  =>  Type::listOf(Types::track()),
                        'args'  =>  [
                            'userId'    =>  Type::nonNull(Type::int()),
                            'title'     =>  Type::string(),
                            'artist'    =>  Type::string(),
                            'query'     =>  Type::string(),
                            'offset'    =>  Type::int(),
                            'limit'     =>  Type::int(),
                            'order'     =>  Type::string()
                        ],
                        'resolve'   =>  function($root, $args){
                            $query = (new TrackSearch($args))->getResults();

                            if(array_key_exists('limit', $args)){
                                $query->limit($args['limit']);
                            }

                            if(array_key_exists('offset', $args)){
                                $query->offset($args['offset']);
                            }

                            if(array_key_exists('order', $args)){
                                $query->addOrderBy($args['order']);
                            }

                            return $query->all();
                        }
                    ],
                ];
            }
        ];

        parent::__construct($config);
    }

}