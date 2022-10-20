<?php

namespace core\entities\rbac;
/**
 * Represent Basic App Roles, which hardcoded in app and
 * couldn't be deleted with CRUD for fool-tolerance reasons.
 */
class DefaultRoles
{
    const USER = 'User';
    const ADMIN = 'Admin';
    const DEV = 'Developer';
    const OWNER = 'Owner';

    public static $default_roles = [
        self::USER,
        self::ADMIN,
        self::DEV,
        self::OWNER
    ];

    /**
     * Answers the question.
     * Uses to refuse to rename and delete default roles for fool-tolerance reasons.
     *
     * @param $name
     * @return bool
     */
    public static function isDefaultRole($name): bool
    {
        return in_array($name, self::$default_roles);
    }
}
