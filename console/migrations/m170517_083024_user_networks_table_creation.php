<?php

use yii\db\Migration;

class m170517_083024_user_networks_table_creation extends Migration
{
    private $table_name = '{{%user_networks}}';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable($this->table_name, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'identity' => $this->string()->notNull(),
            'network' => $this->string(16)->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-user_networks-identity-network}}', $this->table_name, ['identity', 'network'], true);

        $this->createIndex('{{%idx-user_networks-user_id}}', $this->table_name, 'user_id');
        $this->addForeignKey('{{%fk-users-id}}', $this->table_name, 'user_id', '{{%users}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable($this->table_name);
    }
}
