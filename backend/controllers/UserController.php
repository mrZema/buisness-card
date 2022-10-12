<?php

namespace backend\controllers;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use yii\base\Exception;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\forms\user\UserSearch;
use yii\web\NotFoundHttpException;
use core\entities\rbac\DefaultRoles;
use backend\forms\user\UserEditForm;
use backend\forms\user\UserCreateForm;
use core\services\manage\UserManageService;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    private $userManageService;

    public function __construct($id, $module, UserManageService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->userManageService = $service;
    }

    public function behaviors()
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
                        'actions' => [],
                        'roles' => [DefaultRoles::ADMIN],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     *
     * @param integer $id
     * @param string $mode (view or edit)
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $mode = 'view')
    {
        $user = $this->userManageService->find($id);

        $userEditForm = new UserEditForm($user);
        if ($userEditForm->load(Yii::$app->request->post()) && $userEditForm->validate()) {
                try {
                    $this->userManageService->edit($user->id, $userEditForm);
                    Yii::$app->session->setFlash('kv-detail-success', 'User has been successfully edited.');
                    return $this->redirect(['view', 'id' => $user->id]);
                } catch (\RuntimeException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
        }

        return $this->render('view', [
            'user' => $user,
            'userEditForm' => $userEditForm,
            'mode' => $mode
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     * @throws Exception
     */
    public function actionCreate()
    {
        $userCreateForm = new UserCreateForm();
        if ($userCreateForm->load(Yii::$app->request->post()) && $userCreateForm->validate()) {
            try {
                $user = $this->userManageService->create($userCreateForm);
                Yii::$app->session->setFlash('kv-detail-success', 'User has been successfully created.');
                return $this->redirect(['view', 'id' => $user->id]);
            } catch (\RuntimeException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', [
            'userCreateForm' => $userCreateForm,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     */
    public function actionDelete($id)
    {
        $post = Yii::$app->request->post();
        $is_ajax = Yii::$app->request->isAjax && isset($post['kvdelete']);

        $success_msg = Yii::t('yii2mod.settings', 'Setting has been deleted.');
        $success_link = Html::a('<i class="fas fa-hand-point-right"></i>  Click here',
                ['index'], ['class' => 'btn btn-sm btn-success']) . ' to come back to section list.';

        try {
            $this->userManageService->remove($id);
            if ($is_ajax) return Json::encode(['success' => true, 'messages' => ['kv-detail-success' => $success_msg . $success_link]]);
            Yii::$app->session->setFlash('success', $success_msg);
        } catch (\RuntimeException $e) {
            Yii::$app->errorHandler->logException($e);
            if ($is_ajax) return Json::encode(['success' => false, 'messages' => ['kv-detail-error' => $e->getMessage()]]);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }
}
