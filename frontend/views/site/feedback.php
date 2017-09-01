<?php

/**
 * @var $this yii\web\View
 * @var $model \frontend\models\forms\FeedbackForm
 */

use yii\helpers\Html;

$this->title = \Yii::t('site', 'Оставить отзыв');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jumbotron jumbotron-form">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    $form = \yii\bootstrap\ActiveForm::begin([
        'layout'    =>  'horizontal',
        'options'   =>  [
            'class' =>  'mt-5'
        ]
    ]);

    if(\Yii::$app->user->isGuest){
        echo $form->field($model, 'username');
    }

    echo $form
        ->field($model, 'message')
        ->textarea();

    echo Html::tag('div',
        Html::button(\Yii::t('site', 'Оставить отзыв'), ['type' => 'submit', 'class' => 'btn btn-success']),
        [
            'class' =>  'text-center mt-5'
        ]);

    $form::end();
    ?>
</div>
