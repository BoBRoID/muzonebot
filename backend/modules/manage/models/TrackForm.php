<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 6/26/17
 * Time: 11:24 PM
 */

namespace app\modules\manage\models;


use app\models\Song;
use yii\base\Model;

class TrackForm extends Model
{

    public $id;

    public $title;

    public $artist;

    public $duration;

    public $genreID;

    public function rules()
    {
        return [
            [['id', 'genreID', 'duration'], 'number'],
            [['title', 'artist'], 'string']
        ];
    }

    public function save(){
        $song = Song::findOne(['id' => $this->id]);

        if(!$song){
            $song = new Song();
        }

        $song->load([$song->formName() => $this->toArray()]);

        return $song->save();
    }

}