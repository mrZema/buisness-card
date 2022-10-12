<?php

namespace core\services\manage;

use RuntimeException;
use yii\base\Exception;
use core\entities\auth\User;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use backend\forms\user\UserEditForm;
use core\services\rbac\RoleService;
use core\repositories\UserRepository;
use backend\forms\user\UserCreateForm;
use core\services\helpers\TransactionManager;

class UserManageService
{
    private $repository;
    private $roleService;
    private $transaction;

    public function __construct(UserRepository $repository, RoleService $roleService, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->roleService = $roleService;
        $this->transaction = $transaction;
    }

    /**
     * Creates User.
     *
     * @param UserCreateForm $form
     * @return User
     * @throws Exception
     * @throws RuntimeException
     */
    public function create(UserCreateForm $form): User
    {
        $user = User::create(
            $form->username,
            $form->email,
            $form->password
        );
        $this->transaction->wrap(function () use ($user, $form) {
            $this->repository->save($user);
            $this->roleService->assign($user->id, $form->roles);
        });
        return $user;
    }

    /**
     * Edits User.
     *
     * @param int $id
     * @param UserEditForm $form
     * @throws NotFoundHttpException
     * @throws RuntimeException
     */
    public function edit(int $id, UserEditForm $form): void
    {
        $user = $this->repository->get($id);
        $user->edit(
            $form->username,
            $form->email,
            $form->status
        );
        $this->transaction->wrap(function () use ($user, $form) {
            $this->repository->save($user);
            $this->roleService->assign($user->id, $form->roles);
        });
    }

    /**
     *Removes User from DB.
     *
     * @param int $id
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws StaleObjectException
     * @throws RuntimeException
     */
    public function remove(int $id): void
    {
        $user = $this->repository->get($id);
        $this->repository->remove($user);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     * @return User|null the loaded model
     * @throws NotFoundHttpException
     */
    public function find(int $id): User
    {
        return $this->repository->get($id);
    }
}
