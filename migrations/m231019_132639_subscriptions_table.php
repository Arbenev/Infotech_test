<?php

use yii\db\Migration;

/**
 * Class m231019_132639_subscriptions_table
 */
class m231019_132639_subscriptions_table extends Migration
{

    const SUBSCRIPTIONS_TABLE_NAME = 'subscription';
    const USER_TABLE_NAME = 'user';
    const AUTHOR_TABLE_NAME = 'author';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $columns = [
            'id' => 'INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT',
            'user_id' => 'INT(11) NOT NULL',
            'author_id' => 'INT(11) NOT NULL',
        ];
        $this->createTable(self::SUBSCRIPTIONS_TABLE_NAME, $columns);
        $this->createIndex('UNX_acl', self::SUBSCRIPTIONS_TABLE_NAME, ['user_id', 'author_id'], true);
        $this->addForeignKey('FK_subscription__user', self::SUBSCRIPTIONS_TABLE_NAME, 'user_id', self::USER_TABLE_NAME, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_subscription__author', self::SUBSCRIPTIONS_TABLE_NAME, 'author_id', self::AUTHOR_TABLE_NAME, 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::SUBSCRIPTIONS_TABLE_NAME);
    }
}
