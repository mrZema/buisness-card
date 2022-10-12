<?php

namespace backend\forms\rbac;

use Yii;
use yii\rbac\Rule;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use core\helpers\ViewHelper;

class RoleCreateForm extends Model
{
    public $name;
    public $description;
    public $child_roles;
    public $permissions;
    public $ruleName;
    public $data;

    public $_role;

    public $authManager;

    public function __construct($config = [])
    {
        $this->authManager = Yii::$app->authManager;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 64],
            [['name'], 'checkUnique', 'when' => function () {
                return ($this->_role === null) || ($this->_role->name != $this->name);
            }],
            [['description'], 'string'],
            [['child_roles', 'permissions'], 'default', 'value' => []],
            [['ruleName'], 'checkRule'],
            [['ruleName', 'data'], 'default']
        ];
    }

    /**
     * Check for rule
     */
    public function checkRule()
    {
        $name = $this->ruleName;
        if (!$this->authManager->getRule($name)) {
            try {
                $rule = Yii::createObject($name);
                if ($rule instanceof Rule) {
                    $rule->name = $name;
                    $this->authManager->add($rule);
                } else {
                    $this->addError('ruleName', Yii::t('rbac-admin', 'Invalid rule "{value}"', ['value' => $name]));
                }
            } catch (\Exception $exc) {
                $this->addError('ruleName', Yii::t('rbac-admin', 'Rule "{value}" does not exists', ['value' => $name]));
            }
        }
    }

    /**
     * Check role is unique
     */
    public function checkUnique()
    {
        $value = $this->name;
        if ($this->authManager->getRole($value) !== null || $this->authManager->getPermission($value) !== null) {
            $message = Yii::t('yii', '{attribute} "{value}" has already been taken.');
            $params = [
                'attribute' => $this->getAttributeLabel('name'),
                'value' => $value,
            ];
            $this->addError('name', Yii::$app->getI18n()->format($message, $params, Yii::$app->language));
        }
    }

    /**
     * Returns Role list of application without current Role.
     * Keys and values are name of roles without any changes.
     *
     * @return array
     */
    public function returnRoleList(): array
    {
        $roles = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name');
        unset($roles[$this->name]);
        return $roles;
    }

    /**
     * Return Permission list of application.
     * Keys are names of permissions without any changes,
     * values are processed by function.
     *
     * @return array
     */
    public function returnPermissionList(): array
    {
        return self::transformName(ArrayHelper::map(Yii::$app->authManager->getPermissions(), 'name', 'name'));
    }

    /**
     * Transform name style with ViewHelper function.
     *
     * @param array $array
     * @return array
     */
    protected function transformName(array $array): array
    {
        return array_map(
            function ($text) {
                return ViewHelper::underscoreToUCWords($text);
            },
            $array
        );
    }
}
