<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 26.06.2017
 * Time: 17:44
 */
namespace backend\models;

use common\models\User;
use yii\helpers\ArrayHelper;

class SongSearch extends \yii\base\Model
{
    public $query;

    public $artist;

    public $trackName;

    public $userId;

    public $addedRange;

    public $addedFrom;

    public $addedTo;

    public $deleted = false;

    public function rules()
    {
        return [
            [['query', 'artist', 'trackName', 'addedFrom', 'addedTo'], 'string'],
            [['userId'], 'integer'],
            [['deleted'], 'boolean']
        ];
    }

    public function getResults(){
        $query = Song::find();

        if(!empty($this->query)){
            if($this->query === '!'){
                $query->andWhere(['or', ['artist' => null], ['title' => null]]);
            }else{
                $query->andWhere(['or', ['like', 'artist', $this->query], ['like', 'title', $this->query]]);
            }
        }

        if(!empty($this->artist)){
            if($this->artist === '!'){
                $query->andWhere(['artist' => null]);
            }else{
                $query->andWhere(['like', 'artist', $this->artist]);
            }
        }

        if(!empty($this->trackName)){
            if($this->trackName === '!'){
                $query->andWhere(['title' => null]);
            }else{
                $query->andWhere(['like', 'title', $this->trackName]);
            }
        }

        if(!empty($this->addedFrom)){
            $query->andWhere(['>=', 'added', strtotime($this->addedFrom)]);
        }

        if(!empty($this->addedTo)){
            $query->andWhere(['<=', 'added', strtotime($this->addedTo)]);
        }

        if(!empty($this->userId)){
            $query->andWhere(['in', 'user_id', $this->userId]);
        }

        if(!$this->deleted){
            $query->andWhere(['deleted' => 0]);
        }

        return $query;
    }

    /**
     * @return array
     */
    public function getUsers(){
        $possibleUsers = Song::find()->select('user_id')->groupBy('user_id')->asArray()->all();

        return ArrayHelper::map(User::find()->where(['in', 'id', ArrayHelper::getColumn($possibleUsers, 'user_id')])->asArray()->all(), 'id', 'username');
    }

    public function attributeLabels()
    {
        return [
            'query'     =>  \Yii::t('manage', 'Запрос'),
            'artist'    =>  \Yii::t('manage', 'Исполнитель'),
            'trackName' =>  \Yii::t('manage', 'Название трека'),
            'deleted'   =>  \Yii::t('manage', 'Искать в удалённых'),
            'addedFrom' =>  \Yii::t('manage', 'Добавлен от'),
            'addedTo'   =>  \Yii::t('manage', 'Добавлен до'),
            'user_id'   =>  \Yii::t('manage', 'Добавил пользователь'),
        ];
    }

}