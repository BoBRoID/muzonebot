<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 20.10.17
 * Time: 13:02
 */

namespace tg\tests\unit\models;


use tg\models\TgUserData;

class TgUserDataTest extends \Codeception\Test\Unit
{
    public function testCreation()
    {
        $model = new TgUserData([
            'id'            =>  '1',
            'username'      =>  'username',
            'firstName'     =>  'firstName',
            'lastName'      =>  'lastName',
            'languageCode'  =>  'ru_RU'
        ]);

        expect($model->getId())->equals(1);
        expect($model->getUsername())->equals('username');
        expect($model->getFirstName())->equals('firstName');
        expect($model->getLastName())->equals('lastName');
        expect($model->getLanguageCode())->equals('ru_RU');

    }

}
