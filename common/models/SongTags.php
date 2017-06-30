<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 20.03.17
 * Time: 21:41
 */

namespace common\models;


use yii\base\Model;

class SongTags extends Model
{
    /**
     * @var string название трека
     */
    public $title;

    /**
     * @var string имя исполнителя
     */
    public $artist;

    /**
     * @var int длительность в секундах
     */
    public $duration;

    /**
     * @var int битрейт
     */
    public $bitrate;

    /**
     * @var string название альбома
     */
    public $album;

    /**
     * @var string жанр
     */
    public $genre;

    /**
     * @var integer год записи
     */
    public $year;

    /**
     * @var string обложка альбома
     */
    public $cover;

    /**
     * @var string формат трека
     */
    public $format;


    public function rules()
    {
        return [
            [['title', 'artist'], 'required'],
            [['artist', 'album', 'genre'], 'filter', 'filter' => function($value){
                $badGuys = $this->getFuckingBadGuys();

                foreach($badGuys as &$badGuy){
                    $badGuy = '/([\W]+|)'.$badGuy.'([\W]+|)/';
                }

                return preg_replace($badGuys, '', $value);
            }],
            [['title', 'artist', 'album', 'genre', 'cover', 'format'], 'string'],
            [['duration'], 'filter', 'filter' => function($value){
                return round($value);
            }],
            ['genre', 'filter', 'filter' => function($value){
                return \getid3_id3v1::LookupGenreID($value);
            }],
            [['bitrate'], 'filter', 'filter' => function($value){
                if($value > 1000){
                    $value = $value / 1000;
                }

                return round($value);
            }],
            [['duration', 'bitrate', 'year'], 'integer']
        ];
    }

    private function getFuckingBadGuys(){
        return [
            'mp3-crazy.net',
            'mp3-crazy',
        ];
    }
}