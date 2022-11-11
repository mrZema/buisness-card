<?php

namespace backend\controllers\settings;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use yii\db\ActiveRecord;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\base\InvalidConfigException;
use core\entities\rbac\DefaultRoles;
use yii2mod\settings\models\search\SettingSearch;

class ItemController extends Controller
{
    /**
     * @var string model class name for CRUD operations
     */
    public $modelClass = 'core\entities\settings\Setting';

    /**
     * Returns a list of behaviors that this component should behave as.
     *
     * @return array
     */
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
     * Lists all Settings.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SettingSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Setting.
     *
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     * @throws InvalidConfigException
     */
    public function actionCreate()
    {
        $setting = Yii::createObject($this->modelClass);

        if ($setting->load(Yii::$app->request->post()) && !Yii::$app->request->isPjax && $setting->save()) {
            Yii::$app->session->setFlash('success', Yii::t('yii2mod.settings', 'Setting has been created.'));

            return $this->redirect(['view', 'id' => $setting->id]);
        } else {
            return $this->render('create', [
                'setting' => $setting,
            ]);
        }
    }

    /**
     * Displays a single Item model.
     *
     * @param integer $id
     * @param string $mode (view or edit)
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $mode = 'view')
    {
        $setting = $this->findSetting($id);
        $post = Yii::$app->request->post();

        if ($setting->load($post) && $setting->validate()) {
            try {
                $setting->save();
                Yii::$app->session->setFlash('success', 'Setting has been successfully edited.');
                return $this->redirect(['view', 'id' => $setting->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('view', [
            'setting' => $setting,
            'mode' => $mode
        ]);
    }

    /**
     * Deletes an existing Item.
     *
     * Depends of request type returns string to render by ajax or redirects to the 'index' page.
     *
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionDelete(int $id)
    {
        $setting = $this->findSetting($id);
        $post = Yii::$app->request->post();
        $is_ajax = Yii::$app->request->isAjax && isset($post['kvdelete']);

        $success_msg = Yii::t('yii2mod.settings', 'Setting has been deleted.');
        $success_link = Html::a('<i class="fas fa-hand-point-right"></i>  Click here',
                ['index'], ['class' => 'btn btn-sm btn-success']) . ' to come back to section list.';

        try {
            $setting->delete();
            if ($is_ajax) {
                return Json::encode(['success' => true, 'messages' => ['kv-detail-success' => $success_msg . $success_link]]);
            }
            Yii::$app->session->setFlash('success', $success_msg);
        } catch (\Throwable $e) {
            Yii::$app->errorHandler->logException($e);
            if ($is_ajax) {
                return Json::encode(['success' => false, 'messages' => ['kv-detail-error' => $e->getMessage()]]);
            }
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds a Setting model based on its primary key value.
     *
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     * @return ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findSetting(int $id)
    {
        $settingModelClass = $this->modelClass;

        /* @var $settingModelClass ActiveRecord*/
        if (($model = $settingModelClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('yii2mod.settings', 'The requested page does not exist.'));
        }
    }
}
