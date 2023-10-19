<?php

namespace app\models\Auth;

use \yii\db\ActiveRecord;

/**
 * Access control list row
 *
 * @author acround
 *
 * @property int $role_id Role
 * @property int $access_id Access
 */
class Acl extends ActiveRecord
{

    public static function checkAccess(string $role, string $access)
    {
        return self::find()->
                join('INNER JOIN', Role::tableName(), Role::tableName() . '.[[id]]=' . self::tableName() . '.[[role_id]]')->
                join('INNER JOIN', Access::tableName(), Access::tableName() . '.[[id]]=' . self::tableName() . '.[[access_id]]')->
                where(Role::tableName() . '.[[name]]=\'' . $role . '\'')->
                andWhere(Access::tableName() . '.[[name]]=\'' . $access . '\'')->count();
    }
}
