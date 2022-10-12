<?php

use yii\db\Migration;
use yii\rbac\DbManager;
use yii\base\InvalidConfigException;

/**
 * Initializes RBAC tables
 * with data integrity restrictions for removal of items which are children or assigned to user.
 *
 * Inherited from Yii2 rbac migrations, compatibility with no mysql was abandoned.
 *
 * @author mrZema
 * @since 2.0.32
 */
class m170531_203800_rbac_init extends Migration
{
    /**
     * @return bool|void
     * @throws InvalidConfigException
     */
    public function safeUp()
    {
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($authManager->ruleTable, [
            'name' => $this->string(64)->notNull(),
            'data' => $this->binary(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY ([[name]])',
        ], $tableOptions);

        $this->createTable($authManager->itemTable, [
            'name' => $this->string(64)->notNull(),
            'type' => $this->smallInteger()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->binary(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY ([[name]])',
        ], $tableOptions);
        $this->createIndex('{{%idx-auth_item-type}}', $authManager->itemTable, 'type');
        $this->addForeignKey('{{%fk-auth_item-rule_name}}', $authManager->itemTable,
            'rule_name', $authManager->ruleTable, 'name', 'SET NULL', 'CASCADE');

        $this->createTable($authManager->itemChildTable, [
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull(),
            'PRIMARY KEY ([[parent]], [[child]])',
        ], $tableOptions);
        $this->addForeignKey('{{%fk-auth_item_child-parent_name}}', $authManager->itemChildTable,
            'parent', $authManager->itemTable, 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('{{%fk-auth_item_child-child_name}}', $authManager->itemChildTable,
            'child', $authManager->itemTable, 'name', 'RESTRICT', 'CASCADE');


        $this->createTable($authManager->assignmentTable, [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer(),
            'PRIMARY KEY ([[item_name]], [[user_id]])',
        ], $tableOptions);
        $this->addForeignKey('{{%fk-auth_assignment-item_name}}', $authManager->assignmentTable,
            'item_name', $authManager->itemTable, 'name', 'RESTRICT', 'CASCADE');
        $this->createIndex('{{%idx-auth_assignment-user_id}}', $authManager->assignmentTable, 'user_id');
        $this->addForeignKey('{{%fk-auth_assignment-user_id}}', $authManager->assignmentTable,
            'user_id', '{{%users}}', 'id', 'CASCADE');
    }

    /**
     * @return bool|void
     * @throws InvalidConfigException
     */
    public function safeDown()
    {
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;

        $this->dropTable($authManager->assignmentTable);
        $this->dropTable($authManager->itemChildTable);
        $this->dropTable($authManager->itemTable);
        $this->dropTable($authManager->ruleTable);
    }

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
}
