<?php

namespace app\models\Auth;

use \yii\db\ActiveRecord;

/**
 * User role
 *
 * @author acround
 *
 * @property int $id Primary
 * @property string $name Role name
 */
class Role extends ActiveRecord
{

    public function getAcl()
    {
        return $this->hasMany(Acl::className(), ['id' => 'role_id']);
    }
}
