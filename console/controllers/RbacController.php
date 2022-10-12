<?php

namespace console\controllers;

use Yii;
use Exception;
use ReflectionException;
use yii\console\Controller;
use yii\db\IntegrityException;
use core\entities\rbac\AppRules;
use core\entities\rbac\AppPermissions;

class RbacController extends Controller
{
    private $authManager;
    private $app_permission_list;

    /**
     * RbacController constructor.
     *
     * @param $id
     * @param $module
     * @param array $config
     * @throws ReflectionException
     */
    public function __construct($id, $module, $config = [])
    {
        $this->authManager = Yii::$app->getAuthManager();
        $this->app_permission_list = AppPermissions::returnAppPermissions();
        parent::__construct($id, $module, $config);
    }

    /**
     * Update Permissions in AuthManager from hardcore PermissionRepository.
     *
     * @throws IntegrityException
     * @throws ReflectionException
     */
    public function actionPermissionsUpdate(): void
    {
        $this->stdout(PHP_EOL . 'Start permission updating.' . PHP_EOL . PHP_EOL);

        $result = $this->deleteOutdatedPermissions();
        $deleted = $result['deleted'];
        $failed = $result['failed'];

        $result = $this->actualizePermissions();
        $created = $result['created'];
        $errors = $result['failed'];

        $result = $this->updateRules();
        $rules_added = $result['rules_added'];
        $rules_assigned = $result['rules_assigned'];
        $errors += $result['failed'];

        //report writing
        $changes = $deleted + $created + $failed;
        $this->stdout('Summary:' . PHP_EOL);
        $this->stdout($deleted . ' permissions have been deleted.' . PHP_EOL);
        $this->stdout($created . ' permissions have been created.' . PHP_EOL);
        if ($failed !== 0) {
            $this->stdout($failed . ' permissions have foreign key constraints, and can\'t be deleted.' . PHP_EOL);
            $this->stdout('This constraints have been created for data integrity and mistakes prevention.' . PHP_EOL);
            $this->stdout('In order to safely delete permission use backend web interface and revoke all user and role assignments to this permission.' . PHP_EOL);
        }
        if ($changes === 0) $this->stdout('No changes happened.' . PHP_EOL);
        $this->stdout($rules_added . ' rules have been added into system.' . PHP_EOL);
        $this->stdout($rules_assigned . ' rules have been assigned to permissions.' . PHP_EOL);

        if ($errors !== 0) $this->stdout('Other errors happened.' . PHP_EOL);
        $this->stdout(PHP_EOL . 'Done!' . PHP_EOL);
    }

    /**
     * Deletes outdated permissions from AuthManager,
     * which don't exist in App Permissions list any more.
     *
     * @return array
     * @throws IntegrityException
     */
    private function deleteOutdatedPermissions(): array
    {
        $existing_permissions = $this->authManager->getPermissions();
        $deleted = 0;
        $failed = 0;

        foreach ($existing_permissions as $permission) {
            if (array_search($permission->name, $this->app_permission_list) === false) {
                try {
                    $this->authManager->remove($permission);
                    $this->stdout('Permission "' . $permission->name . '" have been deleted.' . PHP_EOL . PHP_EOL);
                    $deleted++;
                } catch (IntegrityException $e) {
                    if ($e->errorInfo['1'] === 1451) {
                        //Error: 1451 SQLSTATE: 23000 (ER_ROW_IS_REFERENCED_2)
                        //Message: Cannot delete or update a parent row: a foreign key constraint fails (%s)
                        $this->stdout('Permission "' . $permission->name . '" can\'t  be deleted, because of a foreign key constraint.' . PHP_EOL . PHP_EOL);
                    } else {
                        throw $e;
                    }
                    $failed++;
                }
            }
        }
        return ['deleted' => $deleted, 'failed' => $failed];
    }

    /**
     * Adds new permissions from PermissionRepository into AuthManager storage,
     * and erases ruleName attribute in existing permissions.
     *
     * @return array
     */
    private function actualizePermissions()
    {
        $existing_permissions = $this->authManager->getPermissions();
        $created = 0;
        $rules_erased = 0;
        $filed = 0;

        foreach ($this->app_permission_list as $app_permission) {

            //check if permission exists and erase ruleName if it's set
            foreach ($existing_permissions as $existing_permission) {
                if ($existing_permission->name === $app_permission) {
                    if (isset($existing_permission->ruleName)) {
                        $existing_permission->ruleName = null;
                        try {
                            $this->authManager->update($existing_permission->name, $existing_permission);
                            $rules_erased++;
                        } catch (Exception $e) {
                            $this->stdout($e->getMessage() . PHP_EOL);
                            $filed++;
                        }
                    }
                    continue 2;
                }
            }

            //create new permission if it's not in authManager storage
            $new_permission = $this->authManager->createPermission($app_permission);
            try {
                $this->authManager->add($new_permission);
                $this->stdout('Permission "' . $new_permission->name . '" have been created.' . PHP_EOL . PHP_EOL);
                $created++;
            } catch (Exception $e) {
                $this->stdout($e->getMessage() . PHP_EOL);
                $filed++;
            }
        }
        return ['created' => $created, 'filed' => $filed, 'rules_erased' => $rules_erased];
    }

    /**
     * @throws ReflectionException
     */
    private function updateRules(): array
    {
        $this->authManager->removeAllRules();
        $rules_added = 0;
        $rules_assigned = 0;
        $filed = 0;

        foreach (AppRules::returnAppRules() as $app_rule) {
            $rule = new $app_rule['class'];

            try {
                $this->authManager->add($rule);
                $rules_added++;
            } catch (Exception $e) {
                $this->stdout($e->getMessage() . PHP_EOL);
                $filed++;
            }

            foreach ($app_rule['permissions'] as $rule_permission) {
                $permission = $this->authManager->getPermission($rule_permission);
                $permission->ruleName = $rule->name;

                try {
                    $this->authManager->update($permission->name, $permission);
                    $rules_assigned++;
                } catch (Exception $e) {
                    $this->stdout($e->getMessage() . PHP_EOL);
                    $filed++;
                }
            }
        }
        return ['rules_added' => $rules_added, 'filed' => $filed, 'rules_assigned' => $rules_assigned];
    }
}
