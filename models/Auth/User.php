<?php

namespace app\models\Auth;

use \yii\db\ActiveRecord;

/**
 * User
 * @property int $id Primary
 * @property string $username User name
 * @property string $password Password
 * @property string $authKey Auth key
 * @property string $accessToken Access token
 * @property string $phone Phone number
 * @property int $role_id Role Id
 */
class User extends ActiveRecord
{

    /**
     *
     * @return Role
     */
    public function getRule()
    {
        return $this->hasOne(Role::class, ['id' => 'role_id']);
    }

    /**
     *
     * @return User
     */
    public static function getCurrentUser()
    {
        return \Yii::$app->user->getIdentity();
    }
}
