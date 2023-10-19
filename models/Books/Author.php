<?php

namespace app\models\Books;

use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * Author
 *
 * @author acround
 *
 * @property int $id Primary
 * @property string $first_name First name
 * @property string $middle_name Middle name
 * @property string $last_name Last Name
 */
class Author extends ActiveRecord
{

    public function rules()
    {
        return [
            // атрибут required указывает, что name, email, subject, body обязательны для заполнения
            ['id', 'integer'],
            [['first_name', 'middle_name', 'last_name'], 'string', 'max' => 255],
            [['last_name'], 'required'],
        ];
    }

    public function getFullName()
    {
        return implode(' ', [$this->first_name, $this->middle_name, $this->last_name]);
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

    public static function getFullList()
    {
        return self::find()->orderBy(['last_name' => SORT_ASC, 'first_name' => SORT_ASC, 'middle_name' => SORT_ASC,])->all();
    }

    public static function getTop10($year)
    {
        $query = $this->makeTopQuery($year);
        $authors = $query->all();
        return $this->makeTopList($authors);
    }

    private function makeTopQuery($year)
    {
        $groupByColumns = [
            self::tableName() . '.[[id]]',
            self::tableName() . '.[[first_name]]',
            self::tableName() . '.[[middle_name]]',
            self::tableName() . '.[[last_name]]',
        ];
        return (new Query())->
                        select([self::tableName() . '.*', 'COUNT(*) as number',])->
                        from(self::tableName())->
                        innerJoin('{{' . Book::AUTHOR_LINK_TABLE . '}}', self::tableName() . '.[[id]]=' . '{{' . Book::AUTHOR_LINK_TABLE . '}}.{{author_id}}')->
                        innerJoin(Book::tableName(), Book::tableName() . '.[[id]]=' . '{{' . Book::AUTHOR_LINK_TABLE . '}}.{{book_id}}')->
                        where(Book::tableName() . '.[[year]]=' . $year)->
                        groupBy($groupByColumns)->
                        having('number>0')->
                        orderBy(['number' => SORT_DESC, 'last_name' => SORT_ASC]);
    }

    private function makeTopList($authors)
    {
        $ret = [];
        foreach ($authors as $author) {
            $fullName = [$author['last_name']];
            if ($author['first_name']) {
                $fullName[] = $author['first_name'];
            }
            if ($author['middle_name']) {
                $fullName[] = $author['middle_name'];
            }
            $ret[] = [
                'name' => implode(' ', $fullName),
                'number' => $author['number'],
            ];
        }
        return $ret;
    }
}
