<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 6/29/17
 * Time: 11:34 PM
 * @var $model \backend\models\GetTagsForm
 */
$fileInfo = isset($fileInfo) ? $fileInfo : null;
$this->title = \Yii::t('site', 'Получить список тэгов трека');

$css = <<<'CSS'
pre {outline: 1px solid #ccc; padding: 5px; margin: 5px; }
.string { color: green; }
.number { color: darkorange; }
.boolean { color: blue; }
.null { color: magenta; }
.key { color: red; }
CSS;

$this->registerCss($css);


$js = <<<'JS'
function syntaxHighlight(json) {
    if (typeof json != 'string') {
         json = JSON.stringify(json, undefined, 2);
    }
    json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
        var cls = 'number';
        if (/^"/.test(match)) {
            if (/:$/.test(match)) {
                cls = 'key';
            } else {
                cls = 'string';
            }
        } else if (/true|false/.test(match)) {
            cls = 'boolean';
        } else if (/null/.test(match)) {
            cls = 'null';
        }
        return '<span class="' + cls + '">' + match + '</span>';
    });
}

$(document).ready(function(){
    $.each($('pre.fileInfo'), function(key, item){
        $(item).html(syntaxHighlight($(item).html()));
    })
})
JS;

$this->params['breadcrumbs'][] = ['url' => \yii\helpers\Url::to(['/site/index']), 'label' => \Yii::t('manage', 'Управление системой')];
//$this->params['breadcrumbs'][] = ['url' => \yii\helpers\Url::to(['default/utilities']), 'label' => \Yii::t('manage', 'Список добавленых треков')];
$this->params['breadcrumbs'][] = $this->title;

$form = \yii\widgets\ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
    echo $form->field($model, 'track')->fileInput(),
        $form->field($model, 'hahaha', ['options' => ['style' => 'display: none']]);

    echo \yii\helpers\Html::submitButton(\Yii::t('site', 'Отправить'));
$form->end();

if($fileHash){
    echo \yii\helpers\Html::tag('pre', $fileHash);
}

if($parsedInfo){
    echo \yii\helpers\Html::tag('pre', json_encode($parsedInfo, JSON_PRETTY_PRINT), ['class' => 'fileInfo']);
}

if($fileInfo){
    $this->registerJs($js);
    echo \yii\helpers\Html::tag('pre', json_encode($fileInfo, JSON_PRETTY_PRINT), ['class' => 'fileInfo']);
}