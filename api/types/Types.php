<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 26.09.17
 * Time: 15:11
 */

namespace api\types;


use api\schema\TrackType;
use api\schema\UserType;

class Types
{

    private static $user;
    private static $query;
    private static $track;

    /**
     * @return UserType
     */
    public static function user(){
        return self::$user ?: (self::$user = new UserType());
    }

    /**
     * @return QueryType
     */
    public static function query(){
        return self::$query ?: (self::$query = new QueryType());
    }

    /**
     * @return TrackType
     */
    public static function track(){
        return self::$track ?: (self::$track = new TrackType());
    }

}