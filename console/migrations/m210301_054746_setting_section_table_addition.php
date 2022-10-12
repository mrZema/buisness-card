<?php

use yii\db\Migration;

class m210301_054746_setting_section_table_addition extends Migration
{
    private $table_name = '{{%setting_sections}}';

    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->table_name, [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'description' => $this->string(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-setting_sections-name}}', $this->table_name, 'name', true);
    }

    public function safeDown()
    {
        $this->dropTable($this->table_name);
    }
}
