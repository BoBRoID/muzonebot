<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "notifications_settings".
 *
 * @property integer $user_id
 * @property integer $type
 * @property integer $value
 *
 * @property User $user
 */
class NotificationSettings extends \yii\db\ActiveRecord
{

    const TYPE_WHEN_EXISTS = 1;
    const TYPE_WHEN_SYSTEM_ERROR = 2;
    const TYPE_WHEN_CANT_SAVE = 3;
    const TYPE_WHEN_ADDED = 4;
    const TYPE_WHEN_RECEIVED = 5;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notifications_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type'], 'required'],
            [['user_id', 'type', 'value'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'type' => 'Type',
            'value' => 'Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return array
     */
    public function getPossibleSettings(){
        return [
            self::TYPE_WHEN_EXISTS,
            self::TYPE_WHEN_SYSTEM_ERROR,
            self::TYPE_WHEN_CANT_SAVE,
            self::TYPE_WHEN_ADDED,
            self::TYPE_WHEN_RECEIVED
        ];
    }

    /**
     * @return array
     */
    public static function getTypesDescriptions(){
        return [
            self::TYPE_WHEN_EXISTS          =>  \Yii::t('general', 'трек уже существует'),
            self::TYPE_WHEN_SYSTEM_ERROR    =>  \Yii::t('general', 'произошла системная ошибка'),
            self::TYPE_WHEN_CANT_SAVE       =>  \Yii::t('general', 'невозможно сохранить трек'),
            self::TYPE_WHEN_ADDED           =>  \Yii::t('general', 'трек успешно добавлен'),
            self::TYPE_WHEN_RECEIVED        =>  \Yii::t('general', 'трек получен')
        ];
    }

    /**
     * @return string|null
     */
    public function getTypeDescription(){
        if(!array_key_exists($this->type, self::getTypesDescriptions())){
            return null;
        }

        return self::getTypesDescriptions()[$this->type];
    }
}
