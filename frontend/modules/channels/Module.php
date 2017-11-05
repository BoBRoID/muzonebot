<?php

namespace frontend\modules\channels;

use yii\base\BootstrapInterface;

/**
 * channels module definition class
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\modules\channels\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public function bootstrap($app)
    {
        $app->urlManager->addRules([
            '/channels'             =>  '/channels/default/index',
            '/channels/<action>'    =>  '/channels/default/<action>'
        ], false);
    }
}
