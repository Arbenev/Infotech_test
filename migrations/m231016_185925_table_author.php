<?php

use yii\db\Migration;

/**
 * Class m231016_185925_table_author
 */
class m231016_185925_table_author extends Migration
{

    const TABLE_NAME = 'author';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $columns = [
            'id' => 'INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT',
            'first_name' => 'VARCHAR(255) NOT NULL DEFAULT ""',
            'middle_name' => 'VARCHAR(255) NOT NULL DEFAULT ""',
            'last_name' => 'VARCHAR(255) NOT NULL DEFAULT ""',
        ];
        $this->createTable(self::TABLE_NAME, $columns);
        $this->createIndex('NDX_author_name', self::TABLE_NAME, ['last_name', 'first_name', 'middle_name']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
