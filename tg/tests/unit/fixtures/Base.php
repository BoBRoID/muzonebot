<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 20.10.17
 * Time: 13:40
 */

namespace tg\tests\unit\fixtures;


use Longman\TelegramBot\Entities\Update;
use yii\test\Fixture;

abstract class Base extends Fixture implements BaseInterface
{

    /**
     * @var Update
     */
    public $entity;

    /**
     * @return Update
     */
    public function load(): Update
    {
        if($this->entity === null){
            $this->entity = new Update(json_decode($this->getPost(), true), 'MuzOneBot');
        }

        return $this->entity;
    }

}