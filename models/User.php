<?php

namespace app\models;

use app\models\Auth\User as UserDb;
use app\models\Auth\Role;
use app\models\Auth\Acl;
use app\models\Books\Subscription;

class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{

    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;
    public $phone;
    public $role_id;

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        $userDb = UserDb::find()->where('[[id]]=' . $id)->asArray()->one();
        if ($userDb) {
            return new static($userDb);
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $userDb = UserDb::find()->where('[[accesstoken]] = \'' . $token . '\'')->asArray()->one();
        if ($userDb) {
            return new static($userDb);
        }
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $userDb = UserDb::find()->where('[[username]] = \'' . $username . '\'')->asArray()->one();
        if ($userDb) {
            return new static($userDb);
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }

    /**
     *
     * @return \app\models\User
     */
    public static function getCurrentUser()
    {
        return \Yii::$app->user->getIdentity();
    }

    /**
     *
     * @return Auth\Role
     */
    public function getRole()
    {
        return Role::find()->where('[[id]]=' . $this->role_id)->one();
    }

    public function checkAccess($access)
    {
        return Acl::checkAccess($this->getRole()->name, $access);
    }

    public function hasSubscription($authorId)
    {
        return (new \yii\db\Query)->
                        from(Subscription::tableName())->
                        where('[[user_id]]=' . $this->id)->
                        andWhere('[[author_id]]=' . $authorId)->
                        count() > 0;
    }
}
