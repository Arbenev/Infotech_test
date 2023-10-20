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
    const USER_TABLE_NAME = 'user';
    const ROLE_GUEST_ID = 1;
    const ROLE_USER_ID = 2;

    static protected $roles = [
        self::ROLE_GUEST_ID => 'guest',
        self::ROLE_USER_ID => 'user',
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
    static protected $users = [];

    public function __construct($config = [])
    {
        parent::__construct($config);
        self::$users[] = [
            'username' => 'guest',
            'password' => md5('guest'),
            'phone' => '+381114181000',
            'role_id' => self::ROLE_GUEST_ID,
        ];
        self::$users[] = [
            'username' => 'user',
            'password' => md5('user'),
            'phone' => '+381114182000',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $rolesIds = $this->tableRole();
        $accessIds = $this->tableAccess();
        $this->tableAcl($rolesIds, $accessIds);
        $this->tableUser();
    }

    private function tableRole()
    {
        $columns = [
            'id' => 'INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT',
            'name' => 'VARCHAR(16) NOT NULL',
        ];
        $rolesIds = [];
        $this->createTable(self::ROLE_TABLE_NAME, $columns);
        foreach (self::$roles as $id => $role) {
            $this->insert(self::ROLE_TABLE_NAME, ['id' => $id, 'name' => $role]);
            $rolesIds[$role] = $id;
        }
        return $rolesIds;
    }

    private function tableAccess()
    {
        $columns = [
            'id' => 'INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT',
            'name' => 'VARCHAR(16) NOT NULL',
        ];
        $this->createTable(self::ACCESS_TABLE_NAME, $columns);
        $accessIds = [];
        foreach (self::$access as $access) {
            $this->insert(self::ACCESS_TABLE_NAME, ['name' => $access]);
            $accessIds[$access] = $this->getDb()->getLastInsertID();
        }
        return $accessIds;
    }

    private function tableAcl($rolesIds, $accessIds)
    {
        $columns = [
            'role_id' => 'INT(11) NOT NULL',
            'access_id' => 'INT(11) NOT NULL',
        ];
        $this->createTable(self::ACCESS_LIST_TABLE_NAME, $columns);
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

    private function tableUser()
    {
        $columns = [
            'id' => 'INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT',
            'username' => 'VARCHAR(16) NOT NULL',
            'password' => 'VARCHAR(32) NOT NULL DEFAULT \'\'',
            'authKey' => 'VARCHAR(32) NOT NULL DEFAULT \'\'',
            'accessToken' => 'VARCHAR(32) NOT NULL DEFAULT \'\'',
            'phone' => 'VARCHAR(32) NOT NULL DEFAULT \'\'',
            'role_id' => 'INT(11) NOT NULL DEFAULT ' . self::ROLE_USER_ID,
        ];
        $this->createTable(self::USER_TABLE_NAME, $columns);
        foreach (self::$users as $user) {
            $this->insert(self::USER_TABLE_NAME, $user);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::USER_TABLE_NAME);
        $this->dropTable(self::ACCESS_LIST_TABLE_NAME);
        $this->dropTable(self::ACCESS_TABLE_NAME);
        $this->dropTable(self::ROLE_TABLE_NAME);
    }
}
