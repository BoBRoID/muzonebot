<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 26.09.17
 * Time: 18:48
 */

namespace api\helpers;

class MainHelper
{

    /**
     * @param integer $value
     * @param string $format
     * @return string
     */
    public static function formatTimestampByType(int $value, $format = 'timestamp'): string
    {
        switch($format) {
            case 'relative':
                return \Yii::$app->formatter->asRelativeTime($value);
            case 'datetime':
                return \Yii::$app->formatter->asDatetime($value);
            case 'date':
                return \Yii::$app->formatter->asDate($value);
            case 'time':
                return \Yii::$app->formatter->asTime($value);
            case 'timestamp':
            default:
                return $value;
        }
    }

}