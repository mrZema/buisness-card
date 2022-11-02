<?php

use yii\db\Migration;

class m221020_101633_base_settings_addition extends Migration
{
    public function safeUp()
    {
        $this->batchInsert('{{%setting}}', ['type', 'section', 'key', 'value', 'description', 'created_at', 'updated_at'], [
            ['string', 'FrontEnd', 'AppName', 'ExampleName', 'App name of the site.', time(), time()],
            ['string', 'FrontEnd', 'PhoneNumber', '+1 234 56 789', 'Phone number of the site.', time(), time()],
            ['string', 'FrontEnd', 'Email', 'example@mail.dev', 'Email of the site.', time(), time()],
            ['string', 'BackEnd', 'CreateUserAllowance', 1, 'Allow or not to users with particular permissions (e.g. admins) to create new users.', time(), time()],
            ['string', 'BackEnd', 'CreateRoleAllowance', 1, 'Allow or not to users with particular permissions to create new roles.', time(), time()],
        ]);
    }

    public function safeDown()
    {
        $this->truncateTable('{{%setting}}');
    }
}
