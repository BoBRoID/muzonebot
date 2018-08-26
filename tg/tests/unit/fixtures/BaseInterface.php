<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 20.10.17
 * Time: 13:43
 */

namespace tg\tests\unit\fixtures;


use Longman\TelegramBot\Entities\Update;

interface BaseInterface
{

    public function load(): Update;

    /**
     * @return string
     */
    public function getPost(): string;

}