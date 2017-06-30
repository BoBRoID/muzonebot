<?php

namespace app\bot\Entities;


use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;

class InlineKeyboardList extends InlineKeyboard
{

    /**
     * If no explicit keyboard is passed, try to create one from the parameters.
     *
     * @return array
     */
    protected function createFromParams(){
        $keyboard_type = $this->getKeyboardType();

        $args = func_get_args();

        // Force button parameters into individual rows.
        foreach ($args as &$arg) {
            !is_array($arg) && $arg = [$arg];
        }
        unset($arg);

        $data = reset($args);

        if ($from_data = array_key_exists($keyboard_type, (array)$data)) {
            $args = $data[$keyboard_type];

            // Make sure we're working with a proper row.
            if (!is_array($args)) {
                $args = [];
            }
        }

        $new_keyboard = [];
        $args = array_pop($args);

        foreach ($args as $row) {
            if($row instanceof InlineKeyboardButton){
                $new_keyboard[] = [$row];
            }else{
                $new_keyboard[] = $this->parseRow($row);
            }
        }

        if (!empty($new_keyboard)) {
            if (!$from_data) {
                $data = [];
            }
            $data[$keyboard_type] = $new_keyboard;
        }

        return $data;
    }

}