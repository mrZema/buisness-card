<?php

namespace backend\forms\user;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use core\entities\auth\User;

class UserEditForm extends Model
{
    public $username;
    public $email;
    public $roles;
    public $status;

    public $_user;

    public function __construct(User $user, $config = [])
    {
        $this->username = $user->username;
        $this->email = $user->email;
        $this->roles = ArrayHelper::map(Yii::$app->authManager->getRolesByUser($user->id), 'name', 'name');
        $this->_user = $user;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['username', 'email', 'roles', 'status'], 'required'],
            ['status', 'integer',  'max' => 10],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [['username', 'email'], 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->_user->id]],
        ];
    }
}
