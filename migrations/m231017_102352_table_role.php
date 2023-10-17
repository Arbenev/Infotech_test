<?php

use yii\db\Migration;

/**
 * Class m231017_102352_table_role
 */
class m231017_102352_table_role extends Migration
{

    const ROLE_TABLE_NAME = 'role';
    const ACCESS_TABLE_NAME = 'access';
    const ACCESS_LIST_TABLE_NAME = 'acl';

    static protected $roles = [
        'guest',
        'user',
    ];
    static protected $access = [
        'view',
        'subscription',
        'add',
        'edit',
        'delete',
    ];
    static protected $roleAccess = [
        'guest' => [
            'view',
            'subscription',
        ],
        'user' => [
            'view',
            'subscription',
            'add',
            'edit',
            'delete',
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $columns = [
            'id' => 'INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT',
            'name' => 'VARCHAR(16) NOT NULL',
        ];
        $rolesIds = [];
        $this->createTable(self::ROLE_TABLE_NAME, $columns);
        foreach (self::$roles as $role) {
            $this->insert(self::ROLE_TABLE_NAME, ['name' => $role]);
            $rolesIds[$role] = $this->getDb()->getLastInsertID();
        }
        $this->createTable(self::ACCESS_TABLE_NAME, $columns);
        $accessIds = [];
        foreach (self::$access as $access) {
            $this->insert(self::ACCESS_TABLE_NAME, ['name' => $access]);
            $accessIds[$access] = $this->getDb()->getLastInsertID();
        }
        $accessColumns = [
            'role_id' => 'INT(11) NOT NULL',
            'access_id' => 'INT(11) NOT NULL',
        ];
        $this->createTable(self::ACCESS_LIST_TABLE_NAME, $accessColumns);
        $this->createIndex('UNX_acl', self::ACCESS_LIST_TABLE_NAME, ['role_id', 'access_id'], true);
        $this->addForeignKey('FK_acl__role', self::ACCESS_LIST_TABLE_NAME, 'role_id', self::ROLE_TABLE_NAME, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_acl__access', self::ACCESS_LIST_TABLE_NAME, 'access_id', self::ACCESS_TABLE_NAME, 'id', 'CASCADE', 'CASCADE');
        foreach (self::$roleAccess as $role => $accesses) {
            foreach ($accesses as $access) {
                $aclColumns = [
                    'role_id' => $rolesIds[$role],
                    'access_id' => $accessIds[$access],
                ];
                $this->insert(self::ACCESS_LIST_TABLE_NAME, $aclColumns);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::ACCESS_LIST_TABLE_NAME);
        $this->dropTable(self::ACCESS_TABLE_NAME);
        $this->dropTable(self::ROLE_TABLE_NAME);
    }
}
