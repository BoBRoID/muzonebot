<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 30.08.2017
 * Time: 17:25
 */

namespace common\helpers;


class Emoji
{

    /**
     * 💤
     */
    const ZZZ = '\uD83D\uDCA4';

    /**
     * 🔔
     */
    const BELL = '\u1F514';

    /**
     * 🔕
     */
    const BELL_CANCEL = '\u1F515';

    private static $constants = [
        'zzz'           => self::ZZZ,
        'bell'          =>  self::BELL,
        'bell_cancel'   =>  self::BELL_CANCEL,
    ];

    public static function render(string $name){
        $lName = strtolower($name);

        if(array_key_exists($lName, self::$constants)){
            $name = self::$constants[$lName];
        }

        return json_decode('"'.$name.'"');
    }

}