<?php

use common\widgets\Modal;

Modal::begin([
    'id'        =>  'trackEditModal',
    'header'    =>  \Yii::t('site', 'Редактирование трека')
]);
\yii\widgets\Pjax::begin([
    'enablePushState'   =>  false,
    'timeout'           =>  10000
]);
\yii\widgets\Pjax::end();
Modal::end();