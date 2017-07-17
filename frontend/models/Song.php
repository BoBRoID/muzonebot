<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 17.07.2017
 * Time: 18:05
 */

namespace frontend\models;


class Song extends \common\models\Song
{

    public function behaviors()
    {
        return [
            'LoggableBehavior'  =>  [
                'class'     =>  'sammaye\audittrail\LoggableBehavior',
                'ignored'   =>  ['last_update']
            ]
        ];
    }

}