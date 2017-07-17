<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 03.07.2017
 * Time: 12:18
 */

namespace frontend\models\forms;

use frontend\models\Song;
use yii\base\Model;

class TrackForm extends Model
{

    public $trackID;

    public $title;

    public $artist;

    public $genreID;

    public function rules()
    {
        return [
            [['title', 'artist'], 'string'],
            [['title', 'artist'], 'required'],
            [['genreID'], 'number']
        ];
    }

    public function save(){
        $song = Song::findOne(['id' => $this->trackID]);

        if(!$song){
            $song = new Song();
        }

        $song->setAttributes([
            'title'     =>  $this->title,
            'artist'    =>  $this->artist,
            'genreID'   =>  $this->genreID
        ]);

        $saved = $song->save();

        if($saved){
            $this->trackID = $song->id;
        }

        return $saved;
    }

    public function attributeLabels()
    {
        return [
            'title'     =>  \Yii::t('site', 'Название'),
            'artist'    =>  \Yii::t('site', 'Исполнитель'),
            'genreID'   =>  \Yii::t('site', 'Жанр')
        ];
    }

}