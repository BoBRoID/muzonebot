<?php


namespace console\helpers;


class Messages
{

    public static function pretify(string $message, bool $makeNewLine = true): void
    {
        $date = date('Y-m-d H:i:s');

        echo "\t>>>\t[{$date}]\t\t{$message}";

        if ($makeNewLine) echo "\r\n";
    }


}