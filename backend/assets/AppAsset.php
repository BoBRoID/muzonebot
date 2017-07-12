<?php

namespace backend\assets;

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
    ];
    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
        \rmrevin\yii\fontawesome\cdn\AssetBundle::class
    ];
}
