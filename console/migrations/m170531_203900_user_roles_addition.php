<?php

use core\entities\rbac\DefaultRoles;
use core\entities\rbac\AppPermissions;
use yii\db\Migration;
use yii\base\InvalidConfigException;
use yii\rbac\DbManager;

class m170531_203900_user_roles_addition extends Migration
{
    /**
     * @return DbManager
     * @throws yii\base\InvalidConfigException
     */
    protected function getAuthManager()
    {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }

        return $authManager;
    }

    /**
     * @return bool|void
     * @throws InvalidConfigException
     */
    public function safeUp()
    {
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;

        $this->batchInsert($authManager->itemTable, ['type', 'name', 'description'], [
            [1, DefaultRoles::USER, 'User'],
            [1, DefaultRoles::ADMIN, 'Admin'],
            [1, DefaultRoles::DEV, 'Developer'],
            [1, DefaultRoles::OWNER, 'Owner'],
            [2, AppPermissions::ROLES_VIEW, 'View Roles'],
            [2, AppPermissions::ROLES_EDIT, 'Edit Roles'],
        ]);

        $this->batchInsert($authManager->itemChildTable, ['parent', 'child'], [
            [DefaultRoles::ADMIN, DefaultRoles::USER],
            [DefaultRoles::DEV, DefaultRoles::ADMIN],
            [DefaultRoles::OWNER, DefaultRoles::DEV],
            [DefaultRoles::OWNER, AppPermissions::ROLES_VIEW],
            [DefaultRoles::OWNER, AppPermissions::ROLES_EDIT],
        ]);

        $this->execute('INSERT INTO' . $authManager->assignmentTable . '(item_name, user_id) SELECT \'user\', u.id FROM {{%users}} u ORDER BY u.id');
        $this->execute('UPDATE' . $authManager->assignmentTable . 'SET `item_name` = \'Owner\' WHERE `auth_assignment`.`user_id` = 1;');
    }

    /**
     * @return bool|void
     * @throws InvalidConfigException
     */
    public function down()
    {
        $authManager = $this->getAuthManager();

        $this->delete($authManager->assignmentTable, ['item_name' => [DefaultRoles::OWNER, DefaultRoles::DEV, DefaultRoles::ADMIN, DefaultRoles::USER]]);
        $this->delete($authManager->itemChildTable, ['child' => [AppPermissions::ROLES_EDIT, DefaultRoles::DEV, DefaultRoles::ADMIN, DefaultRoles::USER]]);
        $this->delete($authManager->itemTable, ['name' => [AppPermissions::ROLES_EDIT, AppPermissions::ROLES_VIEW, DefaultRoles::OWNER, DefaultRoles::DEV, DefaultRoles::ADMIN, DefaultRoles::USER]]);
    }
}
