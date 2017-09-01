<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "language".
 *
 * @property string $language_id
 * @property string $language
 * @property string $country
 * @property string $name
 * @property string $name_ascii
 * @property integer $status
 * @property string $flag
 * @property boolean $default
 */
class Language extends \yii\db\ActiveRecord
{

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'language';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language_id', 'language', 'country', 'name', 'name_ascii', 'status'], 'required'],
            [['status', 'default'], 'integer'],
            [['flag'], 'string'],
            [['language_id'], 'string', 'max' => 5],
            [['language', 'country'], 'string', 'max' => 3],
            [['name', 'name_ascii'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'language_id' => \Yii::t('manage', 'ID языка'),
            'language' => \Yii::t('manage', 'Код языка'),
            'country' => \Yii::t('manage', 'Код страны'),
            'name' => \Yii::t('manage', 'Название'),
            'name_ascii' => \Yii::t('manage', 'Название на английском'),
            'status' => \Yii::t('manage', 'Состояние'),
            'flag' => \Yii::t('manage', 'Флаг'),
            'default' => \Yii::t('manage', 'По умолчанию'),
        ];
    }

    public function getMessages(){
        return $this->hasMany(LanguageTranslate::className(), ['language' => 'language_id']);
    }

}
