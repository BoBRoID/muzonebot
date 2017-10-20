<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 20.10.17
 * Time: 12:42
 */

namespace tg\models;


use yii\base\Model;

class TgUserData extends Model
{

    public $id;

    public $username;

    public $firstName;

    public $lastName;

    public $languageCode;

    public function getId(){
        return $this->id;
    }

    public function getUsername(){
        return $this->username;
    }

    public function getFirstName(){
        return $this->firstName;
    }

    public function getLastName(){
        return $this->lastName;
    }

    public function getLanguageCode(){
        return $this->languageCode;
    }


}