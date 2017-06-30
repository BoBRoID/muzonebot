<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 7/1/17
 * Time: 12:04 AM
 */

namespace frontend\models;


use common\models\UserToken;

class User extends \common\models\User implements \yii\web\IdentityInterface
{

    /**
     * @inheritdoc
     */
    public static function findIdentity($id){
        return self::find()
            ->from(['u' => self::tableName()])
            ->leftJoin(['tk' => UserToken::tableName()], 'tk.user_id = u.id')
            ->andWhere(['tk.token' => $id])
            ->andWhere(['>=', 'expire', time()])
            ->one();
    }


    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null){}

    /**
     * @inheritdoc
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey(){
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey){
        return $this->authKey === $authKey;
    }

}