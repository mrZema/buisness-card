<?php

namespace core\entities\rbac;

use ReflectionClass;
use ReflectionException;

/**
 * List of permissions which are used in application.
 *
 * One way to attach more information to each permission is make array instead of string, like this:
 * ['name' => 'roles_edit', 'description' => '', 'rule' => UnchangeableRoleRule::class, 'group' = 'role_management']
 *
 * It's comfortable inside console methods, when manage permissions or for instance to realize permission grouping,
 * but it's not good when call constant from other project places,
 * cause you need to treat constant as array, it's worse to code reading.
 */
class AppPermissions
{
    /** Other User Management */
    const OTHER_USER_ADMINISTRATE = 'other_user_administrate';
    const OTHER_USER_EDIT = 'other_user_edit';
    const OTHER_USER_STATUS_CHANGE = 'other_user_status_change';
    const OTHER_USER_ROLE_CHANGE = 'other_user_role_change';
    const OTHER_USER_EMAIL_CHANGE = 'other_user_email_change';
    const OTHER_USER_DELETE = 'other_user_delete';

    /** Role Management */
    const ROLES_VIEW = 'roles_view';
    const ROLES_EDIT = 'roles_edit';

    /** App Setting Management */
    const APP_SETTINGS_ADMINISTRATE = 'app_settings_administrate';

    /**Example Permission with Rule */
    const EXAMPLE_PERMISSION = 'example_permission';

    /**
     * Returns array of all actual permissions, which uses generally for console AuthManager Permission updating.
     *
     * Just comment constant and update permissions in storage with console command
     * if want to delete permission from system for a while.
     *
     * @return array
     * @throws ReflectionException
     * @var array
     */
    public static function returnAppPermissions(): array
    {
        $reflection = new ReflectionClass(__CLASS__);
        return array_values($reflection->getConstants());
    }
}
