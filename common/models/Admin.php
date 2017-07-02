<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "admin".
 *
 * @property string $id
 * @property integer $added
 * @property string $added_by
 */
class Admin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'added', 'added_by'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'added' => 'Added',
            'added_by' => 'Added By',
        ];
    }
}
