<?php

namespace core\repositories;

use RuntimeException;
use core\entities\auth\User;
use core\exceptions\NotFoundException;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

class UserRepository
{
    /**
     * Finds User by id.
     *
     * @param int $id
     * @return User|null
     */
    public function find(int $id): ?User
    {
        return User::findOne($id);
    }

    /**
     * Finds active User by name.
     *
     * @param string $username
     * @return User|null
     */
    public function findActiveByUsername(string $username): ?User
    {
        return User::findOne(['username' => $username, 'status' => User::STATUS_ACTIVE]);
    }

    /**
     * Finds active User by id.
     *
     * @param int $id
     * @return User|null
     */
    public function findActiveById(int $id): ?User
    {
        return User::findOne(['id' => $id, 'status' => User::STATUS_ACTIVE]);
    }

    /**
     * Finds User by username or email.
     *
     * @param string $value
     * @return User|null
     */
    public function findByUsernameOrEmail(string $value): ?ActiveRecord
    {
        return User::find()->andWhere(['or', ['username' => $value], ['email' => $value]])->one();
    }

    /**
     * Finds User by network identity.
     *
     * @param string $network
     * @param int $identity
     * @return User|null
     */
    public function findByNetworkIdentity(string $network, int $identity): ?ActiveRecord
    {
        return User::find()->joinWith('networks n')->andWhere(['n.network' => $network, 'n.identity' => $identity])->one();
    }

    /**
     * Finds and returns User by id.
     *
     * @param int $id
     * @return User
     * @throws NotFoundHttpException
     */
    public function get(int $id): User
    {
        return $this->getBy(['id' => $id]);
    }

    /**
     * Returns User by confirmation token.
     *
     * @param string $token
     * @return User
     * @throws NotFoundHttpException
     */
    public function getByEmailConfirmToken(string $token): User
    {
        try {
            return $this->getBy(['email_confirm_token' => $token]);
        } catch (NotFoundException $e) {
            throw new NotFoundException('Email confirm token is incorrect.');
        }
    }

    /**
     * Returns User by email.
     *
     * @param string $email
     * @return User
     * @throws NotFoundHttpException
     */
    public function getByEmail(string $email): User
    {
        return $this->getBy(['email' => $email]);
    }

    /**
     * Returns User by password reset token.
     *
     * @param string $token
     * @return User
     * @throws NotFoundHttpException
     */
    public function getByPasswordResetToken(string $token): User
    {
        return $this->getBy(['password_reset_token' => $token]);
    }

    /**
     * Checks if there is user with particular password reset token.
     *
     * @param string $token
     * @return bool
     */
    public function existsByPasswordResetToken(string $token): bool
    {
        return (bool) User::findByPasswordResetToken($token);
    }

    /**
     * Saves User in DB.
     *
     * @param User $user
     * @throws RuntimeException
     */
    public function save(User $user): void
    {
        if (!$user->save()) {
            throw new RuntimeException('Saving error.');
        }
    }

    /**
     * Removes User from DB.
     *
     * @param User $user
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function remove(User $user): void
    {
        if (!$user->delete()) {
            throw new RuntimeException('Removing error.');
        }
    }

    /**
     * Finds User by condition
     *
     * @param array $condition
     * @return User
     * @throws NotFoundHttpException
     */
    private function getBy(array $condition): ActiveRecord
    {
        if (!$user = User::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundHttpException('User not found.');
        }
        return $user;
    }
}
