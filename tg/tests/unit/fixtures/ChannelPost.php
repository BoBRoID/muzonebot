<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 20.10.17
 * Time: 13:46
 */

namespace tg\tests\unit\fixtures;


class ChannelPost extends Base
{

    public function getPost(): string
    {
        return '{"update_id":536855216, "channel_post":{"message_id":18,"chat":{"id":-1001133084294,"title":"test","type":"channel"},"date":1508496402,"text":"test"}}';
    }

}