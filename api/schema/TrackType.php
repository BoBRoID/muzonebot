<?php
namespace api\schema;

use api\types\Types;
use common\models\Song;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use yii\helpers\Url;

class TrackType extends ObjectType
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
                    'fileId'    =>  [
                        'type'  =>  Type::string(),
                        'description'   =>  \Yii::t('api', 'ID файла')
                    ],
                    'title'    =>  [
                        'type'  =>  Type::string(),
                        'description'   =>  \Yii::t('api', 'Название')
                    ],
                    'artist'    =>  [
                        'type'  =>  Type::string(),
                        'description'   =>  \Yii::t('api', 'Исполнитель')
                    ],
                    'duration'    =>  [
                        'type'  =>  Type::int(),
                        'description'   =>  \Yii::t('api', 'Длительность, секунд')
                    ],
                    'genreID'    =>  [
                        'type'  =>  Type::int(),
                        'description'   =>  \Yii::t('api', 'ID жанра')
                    ],
                    'added'    =>  [
                        'type'          =>  Type::string(),
                        'description'   =>  \Yii::t('api', 'Добавлено'),
                        'args'          =>  [
                            'format'        =>  Type::string()
                        ],
                        'resolve'       =>  function(Song $song, $args){
                            $added = $song->added;

                            if(!array_key_exists('format', $args)){
                                return $added;
                            }

                            switch($args['format']) {
                                case 'relative':
                                    return \Yii::$app->formatter->asRelativeTime($added);
                                case 'datetime':
                                    return \Yii::$app->formatter->asDatetime($added);
                                case 'date':
                                    return \Yii::$app->formatter->asDate($added);
                                case 'time':
                                    return \Yii::$app->formatter->asTime($added);
                                case 'timestamp':
                                default:
                                    return $added;
                            }

                        }
                    ],
                    'user'      =>  [
                        'type'  =>  Types::user()
                    ],
                    'user_id'    =>  [
                        'type'  =>  Type::int(),
                        'description'   =>  \Yii::t('api', 'ID пользователя, который добавил трек')
                    ],
                    'deleted'    =>  [
                        'type'  =>  Type::boolean(),
                        'description'   =>  \Yii::t('api', 'Удалён')
                    ],
                    'isBig'    =>  [
                        'type'  =>  Type::boolean(),
                        'description'   =>  \Yii::t('api', 'Слишком большой (нельзя получить ссылку через телеграм)')
                    ],
                    'last_update'    =>  [
                        'type'  =>  Type::int(),
                        'description'   =>  \Yii::t('api', 'Последнее изменение')
                    ],
                    'url' => [
                        'type' => Type::string(),
                        'description' => \Yii::t('api', 'Ссылка на трек'),
                        'resolve' => function(Song $song, $args){
                            return \Yii::$app->params['frontend_url'].'site/get-track?id='.$song->id;
                        }
                    ],
                    /*
                    'userSong'    =>  [

                    ],*/
                ];
            }
        ];

        parent::__construct($config);
    }

}