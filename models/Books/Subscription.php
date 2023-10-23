<?php

namespace app\models\Books;

use \yii\db\ActiveRecord;
use app\models\Auth\User;

/**
 * Subscription
 *
 * @author acround
 *
 * @property int $id Primary
 * @property int $user_id User id
 * @property int $author_id Author id
 */
class Subscription extends ActiveRecord
{

    public static function exists($userId, $authorId)
    {
        return self::find()->where('[[user_id]]=' . $userId)->andWhere('[[author_id]]=' . $authorId)->count() > 0;
    }

    public static function getUsersByAuthor($authorId)
    {
        return User::find()->
                        innerJoin(self::tableName(), self::tableName() . '.[[user_id]]=' . User::tableName() . '.[[id]]')->
                        where(self::tableName() . '.author_id=:authorId', ['authorId' => $authorId])->
                        all();
    }

    public static function make($userId, $authorId)
    {
        $subscription = new self(['user_id' => $userId, 'author_id' => $authorId]);
        $subscription->save();
        return $subscription;
    }

    public static function remove($userId, $authorId)
    {
        self::find()->where('[[user_id]]=' . $userId)->andWhere('[[author_id]]=' . $authorId)->one()->delete();
    }
}
