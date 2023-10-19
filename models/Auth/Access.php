<?php

namespace app\models\Auth;

use \yii\db\ActiveRecord;

/**
 * Access
 *
 * @property int $id Primary
 * @property string $name Access name
 */
class Access extends ActiveRecord
{

    const ACCESS_VIEW = 'view';
    const ACCESS_SUBSCRIPTION = 'subscription';
    const ACCESS_ADD = 'add';
    const ACCESS_EDIT = 'edit';
    const ACCESS_DELETE = 'delete';
}
