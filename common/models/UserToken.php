<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_token".
 *
 * @property integer $user_id
 * @property string $token
 * @property integer $expire
 *
 * @property User $user
 */
class UserToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['expire', 'default', 'value' => time() + 60 * 60 * 24],
            [['user_id', 'token', 'expire'], 'required'],
            [['user_id', 'expire'], 'integer'],
            [['token'], 'string', 'max' => 64],
            [['token'], 'unique'],
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
            'token' => 'Token',
            'expire' => 'Expire',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
