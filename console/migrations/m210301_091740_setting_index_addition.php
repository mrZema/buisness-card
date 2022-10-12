<?php

use yii\db\Migration;

class m210301_091740_setting_index_addition extends Migration
{
    private $table_name = '{{%setting}}';

    public function safeUp()
    {
        $this->createIndex('{{%idx-setting-section}}', $this->table_name, 'section');
        $this->addForeignKey('{{%fk-setting_sections-name}}', $this->table_name, 'section', '{{%setting_sections}}', 'name', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-setting_sections-name}}', $this->table_name);
        $this->dropIndex('{{%idx-setting-section}}', $this->table_name);
    }
}
