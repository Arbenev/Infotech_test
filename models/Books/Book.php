<?php

namespace app\models\Books;

use \yii\db\ActiveRecord;

/**
 * Book
 *
 * @author acround
 *
 * @property int $id Primary
 * @property string $title Book title
 * @property int $year Year of writing
 * @property string $description Description
 * @property string $isbn ISBN
 * @property string $cover Book cover
 */
class Book extends ActiveRecord
{

    const COVER_NO_COVER = 'book_0000.jpg';
    const AUTHOR_LINK_TABLE = 'book_author';

    public function rules()
    {
        return [
            [['id', 'year'], 'integer'],
            ['title', 'required'],
            [['description', 'isbn', 'cover'], 'string'],
        ];
    }

    public function getAuthorIds()
    {
        $authors = $this->
                        hasMany(Author::className(), ['id' => 'author_id'])->
                        viaTable(self::AUTHOR_LINK_TABLE, ['book_id' => 'id'])->all();
        $authorIds = [];
        foreach ($authors as $author) {
            $authorIds[] = $author->id;
        }
        return $authorIds;
    }

    public function deleteAuthors()
    {
        BookAuthor::deleteAll('[[book_id]]=' . $this->id);
        return $this;
    }

    public function getAuthors()
    {
        $authors = $this->
                        hasMany(Author::className(), ['id' => 'author_id'])->
                        viaTable(self::AUTHOR_LINK_TABLE, ['book_id' => 'id'])->all();
        $authorsStr = [];
        foreach ($authors as $author) {
            $authorsStr[] = $author->getFullName();
        }
        return implode(', ', $authorsStr);
    }

    public static function search()
    {
        $config = [
            'query' => self::find(),
            'key' => 'id',
            'pagination' => [
                'pageSize' => 5,
            ],
        ];
        return new \yii\data\ActiveDataProvider($config);
    }
}
