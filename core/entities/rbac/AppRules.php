<?php

namespace core\entities\rbac;

use ReflectionClass;
use ReflectionException;
use core\entities\rbac\rules\ExampleRule;

/**
 * List of rules which are used in application.
 */
class AppRules
{
    /** Role Management */
    const EXAMPLE_RULE = [
        'class' => ExampleRule::class,
        'permissions' => [
            AppPermissions::EXAMPLE_PERMISSION,
        ]
    ];

    /**
     * Array of all actual Rules, which uses generally for console AuthManager Rules updating.
     *
     * Just comment constant and update permissions in storage with console command
     * if want to delete rule from system for a while.
     *
     * @return array
     * @throws ReflectionException
     * @var array
     */
    public static function returnAppRules(): array
    {
        $reflection = new ReflectionClass(__CLASS__);
        return array_values($reflection->getConstants());
    }
}
