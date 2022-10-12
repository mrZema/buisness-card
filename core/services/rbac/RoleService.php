<?php

namespace core\services\rbac;

use Yii;
use Exception;
use yii\db\Query;
use yii\rbac\Item;
use yii\rbac\Role;
use yii\rbac\Permission;
use yii\helpers\ArrayHelper;
use yii\rbac\ManagerInterface;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use core\entities\rbac\DefaultRoles;
use backend\forms\rbac\RoleEditForm;
use backend\forms\rbac\RoleCreateForm;
use core\services\helpers\TransactionManager;

class RoleService
{
    private $authManager;
    private $transaction;

    public function __construct(ManagerInterface $manager, TransactionManager $transaction)
    {
        $this->authManager = $manager;
        $this->transaction = $transaction;
    }

    /**
     * Creates Role.
     *
     * @param RoleCreateForm $form
     * @return Role
     */
    public function create(RoleCreateForm $form): Role
    {
        $authItem = $this->authManager->createRole(ucwords($form->name));
        $authItem->description = $form->description;

        $this->transaction->wrap(function () use ($authItem, $form) {
            $this->authManager->add($authItem);
            $children = array_merge($form->child_roles, $form->permissions);
            $this->addChildrenToParent($authItem->name, $children);
        });
        return $authItem;
    }

    /**
     * Edits Role.
     *
     * @param string $name
     * @param RoleEditForm $form
     * @throws Exception
     */
    public function edit(string $name, RoleEditForm $form): void
    {
        $role = $this->findRole($name);

        if (!DefaultRoles::isDefaultRole($name)) {
            $role->name = ucwords($form->name);
        }
        $role->description = $form->description;
        if (Yii::$app->user->can(DefaultRoles::DEV)) {
            $role->ruleName = $form->ruleName;
        }

        $this->transaction->wrap(function () use ($name, $role, $form) {
            $this->authManager->update($name, $role);
            $children = array_merge($form->child_roles, $form->permissions);
            $this->addChildrenToParent($role->name, $children);
        });
    }

    /**
     * Removes Role model from DB.
     *
     * @param string $name
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function remove(string $name): void
    {
        if (DefaultRoles::isDefaultRole($name)) {
            throw new ForbiddenHttpException('Impossible to delete Basic Role!');
        }
        $role = $this->findRole($name);
        $this->authManager->remove($role);
    }

    /**
     * Assign Roles to User.
     *
     * @param int $userId
     * @param array $roles
     */
    public function assign(int $userId, array $roles): void
    {
        $this->authManager->revokeAll($userId);
        foreach ($roles as $name) {
            try {
                $role = $this->findRole($name);
                $this->authManager->assign($role, $userId);
            } catch (Exception $exc) {
                Yii::error($exc->getMessage(), __METHOD__);
            }
        }
    }

    /**
     * Adds children to parent.
     *
     * @param string $name
     * @param array $children
     * @throws NotFoundHttpException
     */
    public function addChildrenToParent(string $name, array $children): void
    {
        $parent = $this->findRole($name);
        $this->authManager->removeChildren($parent);
        foreach ($children as $name) {
            try {
                $child = $this->findItems($name);
                $this->authManager->addChild($parent, $child);
            } catch (Exception $exc) {
                Yii::error($exc->getMessage(), __METHOD__);
            }
        }
    }

    /**
     * Returns array of parents, or empty array if item does not have them.
     *
     * @param $name
     * @return array
     */
    public function getParents($name): array
    {
        $relations = (new Query())
            ->from($this->authManager->itemChildTable)
            ->where(['child' => $name])
            ->all($this->authManager->db);
        return ArrayHelper::getColumn($relations, 'parent');
    }

    /**
     * Finds Role Model by name.
     *
     * @param string $name
     * @return Role
     * @throws NotFoundHttpException
     */
    public function findRole(string $name): Role
    {
        $item = $this->authManager->getRole($name);
        if (!$item) {
            throw new NotFoundHttpException('Role with name "' . $name . '" does not exist.');
        }
        return $item;
    }

    /**
     * Finds Role or Permission by name.
     *
     * @param string $name
     * @return Role|Permission
     * @throws NotFoundHttpException
     */
    public function findItems(string $name): Item
    {
        $item = $this->authManager->getRole($name);
        $item = $item ?: $this->authManager->getPermission($name);
        if (!$item) {
            throw new NotFoundHttpException('Role or Permission with name "' . $name . '" does not exist.');
        }
        return $item;
    }
}
