<?php

use yii\db\Migration;

class m200504_111019_log_errors_table_addition extends Migration
{
    private $tableName = '{{%log_errors}}';

    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'level' => $this->tinyInteger()->notNull(),
            'category' => $this->string()->notNull(),
            'log_time' => $this->double()->notNull(),
            'ip' => $this->string(),
            'user_id' => $this->integer(),
            'session_id' => $this->string(),
            'message' => $this->text()->notNull(),
            'sys_info' => $this->text()->notNull()
        ], $tableOptions);

        $this->createIndex('idx_log_level', $this->tableName, 'level');
        $this->createIndex('idx_log_category', $this->tableName, 'category');
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
