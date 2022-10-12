<?php

namespace backend\forms\rbac;

use Yii;
use yii\rbac\Role;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

class RoleEditForm extends RoleCreateForm
{
    public function __construct(Role $role, $config = [])
    {
        $this->name = $role->name;
        $this->description = $role->description;
        $child_roles = ArrayHelper::map(Yii::$app->authManager->getChildRoles($role->name), 'name', 'name');
        ArrayHelper::remove($child_roles, $role->name);
        $this->child_roles = $child_roles;
        $this->permissions = self::transformName(ArrayHelper::map(Yii::$app->authManager->getPermissionsByRole($role->name), 'name', 'name'));
        $this->ruleName = $role->ruleName;
        $this->data = $role->data === null ? null : Json::decode($role->data);
        $this->_role = $role;
        parent::__construct($config);
    }
}
