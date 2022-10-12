<?php

namespace backend\controllers;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use core\entities\rbac\DefaultRoles;
use core\entities\loggers\ErrorRecord;
use core\search\logger\ErrorLogSearch;


class ErrorLogController extends Controller
{
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->viewPath = '@backend/views/loggers/error';
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['get'],
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

    public function actionIndex()
    {
        $searchModel = new ErrorLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'error' => $this->findModel($id),
        ]);
    }

    /**
     * Deletes an existing ErrorRecord.
     *
     * Depends of request type returns string to render by ajax or redirects to the 'index' page.
     *
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionDelete(int $id)
    {
        $errorRecord = $this->findModel($id);
        $post = Yii::$app->request->post();
        $is_ajax = Yii::$app->request->isAjax && isset($post['kvdelete']);

        $success_msg = 'Error record has been deleted.';
        $success_link = Html::a('<i class="fas fa-hand-point-right"></i>  Click here',
                ['index'], ['class' => 'btn btn-sm btn-success']) . ' to come back to error list.';

        try {
            $errorRecord->delete();
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

    public function actionDeleteGroup()
    {
        $post = Yii::$app->request->post();
        $error_records = $post['ids'];

        if (!$error_records) {
            Yii::$app->session->setFlash('warning', 'No one error report chosen.');
            return $this->asJson('No one error report chosen.');
        }

        $success = 0;
        $not_found = 0;
        $del_error = 0;

        foreach ($error_records as $id) {
            try {
                $errorRecord = $this->findModel($id);
                $errorRecord->delete();
                $success += 1;
            } catch (NotFoundHttpException $e) {
                $not_found += 1;
            } catch (\Throwable $e) {
                $del_error +=1;
            }
        }
        if ($success >= 0) Yii::$app->session->setFlash('success', $success.' error report(s) had been deleted.');
        if ($not_found > 0) Yii::$app->session->setFlash('warning', $not_found.' error report(s) are not found.');
        if ($del_error > 0) Yii::$app->session->setFlash('error', $del_error.' error report(s) couldn\'t been deleted.');

        return $this->asJson('Ok');
        //return $this->redirect(['index']);
    }

    /**
     * @param integer $id
     * @return ErrorRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): ErrorRecord
    {
        if (($model = ErrorRecord::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
