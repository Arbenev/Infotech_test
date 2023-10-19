<?php

namespace app\models\Books;

use \yii\db\ActiveRecord;

/**
 * Author
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
}
