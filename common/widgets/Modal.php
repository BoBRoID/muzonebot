<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 12.07.2017
 * Time: 18:07
 */

namespace common\widgets;


use yii\bootstrap\Html;

class Modal extends \yii\bootstrap\Modal
{

    /**
     * Renders the header HTML markup of the modal
     * @return string the rendering result
     */
    protected function renderHeader()
    {
        $button = $this->renderCloseButton();

        if ($button !== null) {
            $this->header .=  "\n".$button;
        }
        if ($this->header !== null) {
            Html::addCssClass($this->headerOptions, ['widget' => 'modal-header']);
            return Html::tag('div', "\n" . $this->header . "\n", $this->headerOptions);
        }

        return null;
    }

}