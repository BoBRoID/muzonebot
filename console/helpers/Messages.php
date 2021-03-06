<?php


namespace console\helpers;


class Messages
{

    public static function pretify(string $message, bool $makeNewLine = true, bool $addDate = true): void
    {
        $str = "\t>>>  ";

        if ($addDate) {
            $date = date('H:i:s');
            $str .= "{$date}  ";
        }

        $str .= "\t{$message}";

        if ($makeNewLine) $str .= "\r\n";

        echo $str;
    }

    private static function makeCurlyBrackets(string $string): string
    {

    }

}