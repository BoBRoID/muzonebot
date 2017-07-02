<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 26.06.2017
 * Time: 17:44
 */
namespace backend\modules\manage\models;

use common\models\Song;
use common\models\User;
use yii\helpers\ArrayHelper;

class SongSearch extends \yii\base\Model
{
    public $query;

    public $artist;

    public $trackName;

    public $userId;

    public function rules()
    {
        return [
            [['query', 'artist', 'trackName'], 'safe'],
            [['userId'], 'integer']
        ];
    }

    public function getResults(){
        $query = Song::find();

        if(!empty($this->query)){
            if($this->query == '!'){
                $query->andWhere(['or', ['artist' => null], ['title' => null]]);
            }else{
                $query->andWhere(['or', ['like', 'artist', $this->query], ['like', 'title', $this->query]]);
            }
        }

        if(!empty($this->artist)){
            if($this->artist == '!'){
                $query->andWhere(['artist' => null]);
            }else{
                $query->andWhere(['like', 'artist', $this->artist]);
            }
        }

        if(!empty($this->trackName)){
            if($this->trackName == '!'){
                $query->andWhere(['title' => null]);
            }else{
                $query->andWhere(['like', 'title', $this->trackName]);
            }
        }

        if(!empty($this->userId)){
            $query->andWhere(['in', 'user_id', $this->userId]);
        }

        return $query;
    }

    /**
     * @return array
     */
    public function getUsers(){
        $possibleUsers = Song::find()->select('user_id')->groupBy('user_id')->asArray()->all();

        return [null => \Yii::t('manage', 'Не выбран')] + ArrayHelper::map(User::find()->where(['in', 'id', ArrayHelper::getColumn($possibleUsers, 'user_id')])->asArray()->all(), 'id', 'username');
    }

}