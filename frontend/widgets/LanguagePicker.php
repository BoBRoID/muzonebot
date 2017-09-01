<?php

namespace frontend\widgets;
use common\models\Language;
use yii\bootstrap\Dropdown;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 30.06.2017
 * Time: 14:27
 */
class LanguagePicker extends \yii\base\Widget
{

    public function run()
    {
        $languagesList = Language::find()
            ->select(['language_id', 'name', 'flag'])
            ->andWhere(['status' => 1])
            ->asArray()
            ->all();

        $items = [];
        $currentLanguage = null;

        foreach($languagesList as $language){
            if((string)\Yii::$app->language === (string)$language['language_id']){
                $currentLanguage = $language;
            }

            $items[] = [
                'label' =>  $language['name'],
                'url'   =>  Url::current(['language' => $language['language_id']])
            ];
        }

        $dropdown = Dropdown::widget([
            'items' =>  $items
        ]);

        return Html::a(
            $currentLanguage['name'].' '.json_decode('"'.$currentLanguage['flag'].'"'),
            '#',
            ['data-toggle' => 'dropdown', 'class' => 'nav-link']
        ).$dropdown;
    }

}