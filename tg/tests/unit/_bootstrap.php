<?php
/**
 * Here you can initialize variables via \Codeception\Util\Fixtures class
 * to store data in global array and use it in Tests.
 *
 * ```php
 * // Here _bootstrap.php
 * \Codeception\Util\Fixtures::add('user1', ['name' => 'davert']);
 * ```
 *
 * In Tests
 *
 * ```php
 * \Codeception\Util\Fixtures::get('user1');
 * ```
 */
use Codeception\Util\Fixtures;
use tg\tests\unit\fixtures\ChannelPost;

Fixtures::add('channelPost', (new ChannelPost())->load());
Fixtures::add('message', (new \tg\tests\unit\fixtures\Message())->load());