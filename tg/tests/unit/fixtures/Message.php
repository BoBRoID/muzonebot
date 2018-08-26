<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 20.10.17
 * Time: 13:38
 */

namespace tg\tests\unit\fixtures;

class Message extends Base {

    public function getPost(): string
    {
        return '{"update_id":536855213, "message":{"message_id":102875,"from":{"id":45322236,"is_bot":false,"first_name":"Mr.","last_name":"Nobody \u269c\ufe0f","username":"SomeWho","language_code":"ru"},"chat":{"id":45322236,"first_name":"Mr.","last_name":"Nobody \u269c\ufe0f","username":"SomeWho","type":"private"},"date":1508496371,"text":"stest"}}';
    }

}