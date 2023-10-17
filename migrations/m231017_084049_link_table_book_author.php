<?php

use yii\db\Migration;

/**
 * Class m231017_084049_link_table_book_author
 */
class m231017_084049_link_table_book_author extends Migration
{

    const TABLE_NAME = 'book_author';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $columns = [
            'book_id' => 'INT(11) NOT NULL',
            'author_id' => 'INT(11) NOT NULL',
        ];
        $this->createTable(self::TABLE_NAME, $columns);
        $this->createIndex('UNX_book_author', self::TABLE_NAME, ['book_id', 'author_id'], true);
        $this->addForeignKey('FK_book_author__book', self::TABLE_NAME, 'book_id', 'book', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_book_author__author', self::TABLE_NAME, 'author_id', 'author', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('FK_book_author__book', self::TABLE_NAME);
        $this->dropForeignKey('FK_book_author__author', self::TABLE_NAME);
        $this->dropTable(self::TABLE_NAME);
    }
}
