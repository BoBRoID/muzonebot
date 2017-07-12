<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 12.07.2017
 * Time: 11:36
 */

namespace frontend\widgets;


use yii\bootstrap\BootstrapPluginAsset;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

class NavBar extends \yii\bootstrap\NavBar
{
    public function init()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        $this->clientOptions = false;
        if (empty($this->options['class'])) {
            Html::addCssClass($this->options, ['navbar', 'navbar-light', 'bg-faded']);
        } else {
            Html::addCssClass($this->options, ['widget' => 'navbar']);
        }
        if (empty($this->options['role'])) {
            $this->options['role'] = 'navigation';
        }
        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'nav');
        echo Html::beginTag($tag, $options);
        if ($this->renderInnerContainer) {
            if (!isset($this->innerContainerOptions['class'])) {
                Html::addCssClass($this->innerContainerOptions, 'container');
            }
            echo Html::beginTag('nav', $this->innerContainerOptions);
        }
        echo Html::beginTag('div', ['class' => 'd-flex justify-content-between']);
        if (!isset($this->containerOptions['id'])) {
            $this->containerOptions['id'] = "{$this->options['id']}-collapse";
        }
        if ($this->brandLabel !== false) {
            Html::addCssClass($this->brandOptions, ['widget' => 'navbar-brand']);
            echo Html::a($this->brandLabel, $this->brandUrl === false ? \Yii::$app->homeUrl : $this->brandUrl, $this->brandOptions);
        }
        echo $this->renderToggleButton();
        echo Html::endTag('div');
        Html::addCssClass($this->containerOptions, ['collapse' => 'collapse', 'widget' => 'navbar-toggleable-xs']);
        $options = $this->containerOptions;
        $tag = ArrayHelper::remove($options, 'tag', 'div');
        echo Html::beginTag($tag, $options);
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $tag = ArrayHelper::remove($this->containerOptions, 'tag', 'div');
        echo Html::endTag($tag);
        if ($this->renderInnerContainer) {
            echo Html::endTag('nav');
        }
        $tag = ArrayHelper::remove($this->options, 'tag', 'nav');
        echo Html::endTag($tag);
        BootstrapPluginAsset::register($this->getView());
    }
}