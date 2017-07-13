<?php
namespace backend\widgets;
use yii\bootstrap\BootstrapPluginAsset;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 12.07.2017
 * Time: 18:44
 */
class NavBar extends \yii\bootstrap\NavBar
{

    public $renderInnerContainer = false;

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
        echo $this->renderToggleButton();
        if ($this->brandLabel !== false) {
            Html::addCssClass($this->brandOptions, ['widget' => 'navbar-brand']);
            echo Html::a($this->brandLabel, $this->brandUrl === false ? \Yii::$app->homeUrl : $this->brandUrl, $this->brandOptions);
        }
        if ($this->renderInnerContainer) {
            if (!isset($this->innerContainerOptions['class'])) {
                Html::addCssClass($this->innerContainerOptions, 'container');
            }
            echo Html::beginTag('nav', $this->innerContainerOptions);
        }
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        if ($this->renderInnerContainer) {
            echo Html::endTag('nav');
        }
        $tag = ArrayHelper::remove($this->options, 'tag', 'nav');
        echo Html::endTag($tag);
        BootstrapPluginAsset::register($this->getView());
    }

    /**
     * Renders collapsible toggle button.
     * @return string the rendering toggle button.
     */
    protected function renderToggleButton()
    {
        $screenReader = Html::tag('span', null, ['class' => 'navbar-toggler-icon']);

        return Html::button($screenReader, [
            'class'         => 'navbar-toggler mobile-sidebar-toggler d-lg-none',
            'data-toggle'   => 'collapse',
            'data-target'   => "#{$this->containerOptions['id']}",
            'aria-controls' =>  $this->containerOptions['id'],
            'aria-expanded' =>  false,
            'aria-label'    =>  $this->screenReaderToggleText
        ]);
    }
}