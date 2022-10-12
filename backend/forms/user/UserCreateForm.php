<?php

namespace backend\forms\user;

use yii\base\Model;
use core\entities\auth\User;

class UserCreateForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $roles;

    public function rules(): array
    {
        return [
            [['username', 'email', 'roles'], 'required'],
            ['email', 'email'],
            [['username', 'email'], 'string', 'max' => 255],
            [['username', 'email'], 'unique', 'targetClass' => User::class],
            ['password', 'string', 'min' => 6],
        ];
    }
}
