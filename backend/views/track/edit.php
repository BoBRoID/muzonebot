<?php
/**
 * @var $trackForm \backend\models\forms\TrackForm
 */
use common\widgets\Alert;
use yii\helpers\Html;

$this->title = \Yii::t('manage', 'Редактирование трека');

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

$(document).on('shown.bs.modal', '#metadataModal', function(){
    var modal = $(this);
    
    $.ajax({
        url: '/manage/track/get-metadata?id=' + $('h1[data-key]').data('key')
    }).success(function(data){
        $(modal).find('.modal-body').html("<pre>" + syntaxHighlight(data) + "</pre>");
    })
});
JS;

$this->registerJs($js);

$this->params['breadcrumbs'][] = ['url' => \yii\helpers\Url::to(['/site/index']), 'label' => \Yii::t('manage', 'Управление системой')];
$this->params['breadcrumbs'][] = ['url' => \yii\helpers\Url::to(['/track/index']), 'label' => \Yii::t('manage', 'Список добавленых треков')];
$this->params['breadcrumbs'][] = $this->title;

if(!\Yii::$app->request->isAjax){
    echo Html::tag('h1', $this->title, ['data-key' => $trackForm->id]);
}else{
    echo Alert::widget();
}

echo $this->render('_form', [
    'trackForm' =>  $trackForm,
]);

\yii\bootstrap\Modal::begin([
    'id'        =>  'metadataModal',
    'header'    =>  \Yii::t('site', 'Метаданные')
]);
echo Html::tag('pre');
\yii\bootstrap\Modal::end();