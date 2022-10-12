<?php

namespace backend\controllers\rbac;

use Yii;
use Exception;
use yii\rbac\Item;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\forms\rbac\RoleEditForm;
use core\services\rbac\RoleService;
use backend\forms\rbac\RoleCreateForm;
use core\entities\rbac\AppPermissions;
use mdm\admin\models\searchs\AuthItem as AuthItemSearch;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * RoleController implements the CRUD actions for RBAC Role model.
 */
class RoleController extends Controller
{
    private $roleService;

    public function __construct($id, $module, RoleService $roleService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->roleService = $roleService;
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['get'],
                    'create' => ['get', 'post'],
                    'view' => ['get', 'post'],
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'delete'],
                        'roles' => [AppPermissions::ROLES_EDIT],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view'],
                        'roles' => [AppPermissions::ROLES_VIEW],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Role models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthItemSearch(['type' => Item::TYPE_ROLE]);
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Role model.
     *
     * @param string $name
     * @param string $mode (view or edit)
     * @return mixed
     * @throws Exception
     */
    public function actionView(string $name, $mode = 'view')
    {
        $role = $this->roleService->findRole($name);

        $roleEditForm = new RoleEditForm($role);

        $userCanEdit = Yii::$app->user->can(AppPermissions::ROLES_EDIT);

        if ($userCanEdit && $roleEditForm->load(Yii::$app->request->post()) && $roleEditForm->validate()) {
            try {
                $this->roleService->edit($name, $roleEditForm);
                Yii::$app->session->setFlash('kv-detail-success', 'Role has been successfully edited.');
                return $this->redirect(['view', 'name' => $roleEditForm->name]);
            } catch (\RuntimeException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('view', [
            'roleEditForm' => $roleEditForm,
            'mode' => $userCanEdit ? $mode : 'view'
        ]);
    }

    /**
     * Creates a new Role model.
     *
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $roleCreateForm = new RoleCreateForm();
        if ($roleCreateForm->load(Yii::$app->getRequest()->post()) && $roleCreateForm->validate()) {
            try {
                $role = $this->roleService->create($roleCreateForm);
                Yii::$app->session->setFlash('kv-detail-success', 'Role has been successfully created.');
                return $this->redirect(['view', 'name' => $role->name]);
            } catch (\RuntimeException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', ['roleCreateForm' => $roleCreateForm]);
    }

    /**
     * Deletes an existing Role.
     *
     * Prohibits deletion if role has at least one parent.
     * Depends of request type returns string to render by ajax or redirects to the 'index' page.
     *
     * @param string $name
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionDelete(string $name)
    {
        $post = Yii::$app->request->post();
        $is_ajax = Yii::$app->request->isAjax && isset($post['kvdelete']);

        $parents = $this->roleService->getParents($name);

        $success_msg = Yii::t('yii2mod.settings', 'Role has been deleted.');
        $success_link = Html::a('<i class="fas fa-hand-point-right"></i>  Click here',
                ['index'], ['class' => 'btn btn-sm btn-success']) . ' to come back to section list.';
        $error_msg = 'Impossible to delete Role witch has at least one parent. To delete this Role 
                        you need to revoke it from next parents: ' . ucwords(implode(', ', $parents));
        if (!empty($parents)) {
            if ($is_ajax) return Json::encode(['success' => false, 'messages' => ['kv-detail-warning' => $error_msg]]);
            Yii::$app->session->setFlash('warning', $error_msg);
        } else {
            try {
                $this->roleService->remove($name);
                if ($is_ajax) return Json::encode(['success' => true, 'messages' => ['kv-detail-success' => $success_msg . $success_link]]);
                Yii::$app->session->setFlash('success', $success_msg);
            } catch (NotFoundHttpException $e) {
                Yii::$app->errorHandler->logException($e);
                if ($is_ajax) return Json::encode(['success' => false, 'messages' => ['kv-detail-error' => $e->getMessage()]]);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->redirect(['index']);
    }
}
