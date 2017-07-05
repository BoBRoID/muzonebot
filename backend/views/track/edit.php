<?php
/**
 * @var $trackForm \backend\models\TrackForm
 */
use yii\bootstrap\Html;

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

$(document).on('show.bs.modal', '#metadataModal', function(){
    $(this).find('.modal-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div>');
}).on('shown.bs.modal', '#metadataModal', function(){
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
$this->params['breadcrumbs'][] = ['url' => \yii\helpers\Url::to(['/site/tracks']), 'label' => \Yii::t('manage', 'Список добавленых треков')];
$this->params['breadcrumbs'][] = $this->title;

echo Html::tag('h1', $this->title, ['data-key' => $trackForm->id]);

$form = \yii\bootstrap\ActiveForm::begin(['layout' => 'horizontal']);

echo $form->field($trackForm, 'title'),
    $form->field($trackForm, 'artist'),
    $form->field($trackForm, 'duration'),
    $form->field($trackForm, 'genreID');

echo Html::tag('div', Html::button(\Yii::t('manage', 'Просмотреть метаданные'), ['class' => 'btn btn-warning', 'data-toggle' => 'modal', 'data-target' => '#metadataModal']), ['class' => 'text-center']);
echo Html::tag('br');
echo Html::tag('div', Html::submitButton(\Yii::t('manage', 'Сохранить изменения')), ['class' => 'text-center']);
echo Html::tag('br');
echo Html::tag('div', Html::a(\Yii::t('manage', 'К результатам поиска'), \yii\helpers\Url::previous('tracks'), ['class' => 'btn btn-info']), ['class' => 'text-center']);

$form->end();

\yii\bootstrap\Modal::begin([
    'id'        =>  'metadataModal',
    'header'    =>  \Yii::t('site', 'Метаданные')
]);
echo Html::tag('pre');
\yii\bootstrap\Modal::end();