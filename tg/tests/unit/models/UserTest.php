<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 20.10.17
 * Time: 13:27
 */

namespace tg\tests\unit\models;


use Codeception\Util\Debug;
use Codeception\Util\Fixtures;
use Longman\TelegramBot\Entities\ChannelPost;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\Update;
use tg\models\User;

class UserTest extends \Codeception\Test\Unit
{

    public function testCreationFromUpdate(){
        $entities = [
            'channel post'  =>  Fixtures::get('channelPost'),
            'message'       =>  Fixtures::get('message'),
        ];

        /**
         * @var $update Update
         */
        foreach($entities as $name => $update){
            $user = User::initializeBotUser($update);

            $this->assertNotEquals(null, $user, 'Failed user creation with entity '.$name);

            /**
             * @var $entity ChannelPost|Message
             */
            $entity = $update->getUpdateContent();

            switch(get_class($entity)){
                case ChannelPost::class:
                    /**
                     * @var $entity ChannelPost
                     */
                    $this->assertEquals($entity->getChat()->getId(), $user->id);
                    $this->assertEquals($entity->getChat()->getTitle(), $user->first_name);
                    break;
                case Message::class:
                default:
                    /**
                     * @var $entity Message
                     */
                $this->assertEquals($entity->getFrom()->getId(), $user->id);
                    $this->assertEquals($entity->getFrom()->getFirstName(), $user->first_name);
                    $this->assertEquals($entity->getFrom()->getLastName(), $user->last_name);
                    $this->assertEquals($entity->getFrom()->getUsername(), $user->username);
                    $this->assertEquals($entity->getFrom()->getLanguageCode(), $user->language_code);
                    break;
            }
        }
    }

}