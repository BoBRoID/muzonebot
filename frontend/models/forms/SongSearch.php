<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 6/23/17
 * Time: 11:13 PM
 */

namespace frontend\models\forms;


use common\models\Song;
use yii\base\Model;
use yii\db\ActiveQuery;

class SongSearch extends Model
{

    public $query;

    public $artist;

    public $trackName;

    public function rules()
    {
        return [
            [['query', 'artist', 'trackName'], 'safe']
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getResults(){
        $query = Song::find()
            ->andWhere(['deleted' => 0]);

        if(!empty($this->query)){
            $query->andWhere(['or', ['like', 'artist', $this->query], ['like', 'title', $this->query]]);
        }

        if(!empty($this->artist)){
            $query->andWhere(['like', 'artist', $this->artist]);
        }

        if(!empty($this->trackName)){
            $query->andWhere(['like', 'title', $this->trackName]);
        }

        return $query;
    }

}