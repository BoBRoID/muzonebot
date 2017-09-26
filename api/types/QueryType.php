<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 26.09.17
 * Time: 15:10
 */

namespace api\types;


use api\models\TrackSearch;
use common\models\User;
use common\models\UserSongs;
use frontend\models\forms\SongSearch;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use yii\helpers\ArrayHelper;

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
                    'tracks'    =>  [
                        'type'  =>  Type::listOf(Types::track()),
                        'args'  =>  [
                            'title'     =>  Type::string(),
                            'artist'    =>  Type::string(),
                            'query'     =>  Type::string(),
                        ],
                        'resolve'   =>  function($root, $args){
                            $query = new SongSearch($args);

                            return $query->getResults()->all();
                        }
                    ],
                    'userTracks'    =>  [
                        'type'  =>  Type::listOf(Types::track()),
                        'args'  =>  [
                            'userId'    =>  Type::nonNull(Type::int()),
                            'title'     =>  Type::string(),
                            'artist'    =>  Type::string(),
                            'query'     =>  Type::string()
                        ],
                        'resolve'   =>  function($root, $args){
                            return (new TrackSearch($args))->getResults()->all();
                        }
                    ],
                ];
            }
        ];

        parent::__construct($config);
    }

}