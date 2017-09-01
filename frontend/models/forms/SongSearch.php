<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 6/23/17
 * Time: 11:13 PM
 */

namespace frontend\models\forms;

use frontend\models\Song;
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

        foreach($this->filtersFields() as $field){
            if(!empty($this->$field)){
                $query->andWhere(['like', $field, $this->$field]);
            }
        }

        return $query;
    }

    /**
     * @return array
     */
    protected function filtersFields(): array
    {
        return [
            'artist', 'trackName'
        ];
    }

    /**
     * @return bool
     */
    public function filtersFilled(): bool
    {
        $filled = false;

        foreach($this->filtersFields() as $field){
            if(!empty($this->$field)){
                $filled = true;
            }
        }

        return $filled;
    }

}