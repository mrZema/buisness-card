<?php

namespace backend\controllers\settings;


use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use core\entities\rbac\DefaultRoles;
use core\search\settings\SectionSearch;
use backend\forms\settings\SectionForm;
use core\services\settings\SectionService;

class SectionController extends Controller
{
    private $service;

    public function __construct($id, $module, SectionService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
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
                        'actions' => [],
                        'roles' => [DefaultRoles::DEV],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays and Edits a single Section model.
     *
     * @param integer $id
     * @param string $mode (view or edit)
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id, $mode = 'view')
    {
        $section = $this->service->find($id);

        $sectionForm = new SectionForm($section);
        if ($sectionForm->load(Yii::$app->request->post()) && $sectionForm->validate()) {
            try {
                $this->service->edit($section->id, $sectionForm);
                Yii::$app->session->setFlash('success', 'Section has been successfully edited.');
                return $this->redirect(['view', 'id' => $section->id]);
            } catch (\RuntimeException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('view', [
            'section' => $section,
            'sectionForm' => $sectionForm,
            'mode' => $mode
        ]);
    }

    /**
     * Creates a new Section.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $sectionForm = new SectionForm();
        if ($sectionForm->load(Yii::$app->request->post()) && $sectionForm->validate()) {
            try {
                $section = $this->service->create($sectionForm);
                Yii::$app->session->setFlash('kv-detail-success', 'Section has been successfully created.');
                return $this->redirect(['view', 'id' => $section->id]);
            } catch (\RuntimeException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', [
            'sectionForm' => $sectionForm,
        ]);
    }

    /**
     * Deletes an existing Section.
     *
     * Prohibits deletion if section has at least one child.
     * Depends of request type returns string to render by ajax or redirects to the 'index' page.
     *
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionDelete(int $id)
    {
        $post = Yii::$app->request->post();
        $is_ajax = Yii::$app->request->isAjax && isset($post['kvdelete']);

        $success_msg = 'Section has been successfully deleted.';
        $success_link = Html::a('<i class="fas fa-hand-point-right"></i>  Click here',
                ['index'], ['class' => 'btn btn-sm btn-success']) . ' to come back to section list.';
        $error_msg = 'Impossible to delete Section witch has attached Settings. To manage them' .
            Html::a('<i class="fas fa-hand-point-right"></i> Click here',
                ['/settings/item/index'], ['class' => 'btn btn-sm btn-warning']);

        if ($this->service->hasChildren($id)) {
            if ($is_ajax) return Json::encode(['success' => false, 'messages' => ['kv-detail-warning' => $error_msg]]);
            Yii::$app->session->setFlash('warning', $error_msg);
        } else {
            try {
                $this->service->remove($id);
                if ($is_ajax) return Json::encode(['success' => true, 'messages' => ['kv-detail-success' => $success_msg . $success_link]]);
                Yii::$app->session->setFlash('success', $success_msg);
            } catch (\RuntimeException $e) {
                Yii::$app->errorHandler->logException($e);
                if ($is_ajax) return Json::encode(['success' => false, 'messages' => ['kv-detail-error' => $e->getMessage()]]);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->redirect(['index']);
    }
}
