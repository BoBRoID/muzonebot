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
class DashboardAsset extends AssetBundle
{
    public $sourcePath = '@app/web/static';
    public $js = [
        'js/views/main.js',
    ];
    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
        \rmrevin\yii\fontawesome\cdn\AssetBundle::class,
        BootstrapPluginAsset::class,
        ChartJsAsset::class
    ];
}
