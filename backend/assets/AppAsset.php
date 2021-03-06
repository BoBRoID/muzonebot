<?php

namespace backend\assets;

use dosamigos\chartjs\ChartJsAsset;
use yii\bootstrap\BootstrapPluginAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;
use yii\bootstrap\BootstrapAsset;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@app/web/static';
    public $css = [
        'css/simple-line-icons.css',
        'css/style.css',
        'css/custom.css',
    ];
    public $js = [
        'js/pace.min.js',
        'js/app.js',
        'js/main.js'
    ];
    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
        \rmrevin\yii\fontawesome\cdn\AssetBundle::class,
        BootstrapPluginAsset::class,
    ];
}
