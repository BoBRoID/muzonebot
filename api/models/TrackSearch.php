<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 26.09.17
 * Time: 17:46
 */

namespace api\models;


use common\models\Song;
use common\models\UserSongs;
use yii\base\Model;
use yii\db\ActiveQuery;

class TrackSearch extends Model
{

    public $query;

    public $artist;

    public $title;

    public $deleted = false;

    public $isBig = false;

    public $userId;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['query', 'artist', 'title'], 'string'],
            [['deleted', 'isBig'], 'boolean'],
            [['userId'], 'integer']
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getResults(): ActiveQuery
    {
        $query = Song::find()->from(['s' => Song::tableName()]);

        if(!empty($this->userId)){
            $query
                ->leftJoin(['us' => UserSongs::tableName()], 'us.song_id = s.id')
                ->andWhere(['us.user_id' => $this->userId])
                ->addOrderBy('us.added DESC');
        }

        if (!empty($this->query)) {
            $query->andWhere(['or', ['like', 'artist', $this->query], ['like', 'title', $this->query]]);
        }

        foreach ($this->filtersFields() as $field) {
            if (!empty($this->$field)) {
                $query->andWhere(['like', $field, $this->$field]);
            }
        }

        foreach($this->boolFields() as $field){
            if (!empty($this->$field)) {
                $query->andWhere([$field => $this->$field ? true : false]);
            }
        }

        return $query->addOrderBy('s.added DESC');
    }

    /**
     * @return array
     */
    protected function boolFields(): array
    {
        return ['deleted', 'isBig'];
    }

    /**
     * @return array
     */
    protected function filtersFields(): array
    {
        return [
            'artist', 'title'
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