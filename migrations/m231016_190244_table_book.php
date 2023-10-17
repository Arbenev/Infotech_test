<?php

use yii\db\Migration;

/**
 * Class m231016_190244_table_book
 */
class m231016_190244_table_book extends Migration
{

    const TABLE_NAME = 'book';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $columns = [
            'id' => 'INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT',
            'title' => 'VARCHAR(255) NOT NULL DEFAULT ""',
            'year' => 'INT(11) NULL',
            'description' => 'TEXT NULL',
            'isbn' => 'VARCHAR(32) NULL',
            'cover' => 'VARCHAR(256) NULL',
        ];
        $this->createTable(self::TABLE_NAME, $columns);
        $this->createIndex('NDX_book_title', self::TABLE_NAME, 'title');
        $this->createIndex('NDX_book_year', self::TABLE_NAME, 'year');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
