<?php

namespace app\models\Books;

use \yii\db\ActiveRecord;

/**
 * Link Book->Author
 *
 * @author acround
 * @property int $book_id Book id
 * @property int $author_id Author_id
 */
class BookAuthor extends ActiveRecord
{

    const TABLE_NAME = '{{book_author}}';

    public function rules()
    {
        return [
            [['book_id', 'author_id'], 'integer'],
            [['book_id', 'author_id'], 'required'],
        ];
    }

    public static function tableName()
    {
        return self::TABLE_NAME;
    }

    public function getLinks($bookId)
    {
        return self::find()->where('[[book_id]]=' . $bookId)->all();
    }
}
