<?php

namespace backend\controllers\settings;

use core\entities\rbac\AppPermissions;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use core\entities\settings\Item;
use core\services\settings\SectionService;

class AppController extends Controller
{
    private $sectionService;

    public function __construct($id, $module, SectionService $sectionService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->sectionService = $sectionService;
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['get', 'post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [],
                        'roles' => [AppPermissions::APP_SETTINGS_ADMINISTRATE],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $sections = $this->sectionService->findAllActive();
        $sections = ArrayHelper::index($sections, 'name');

        $post = Yii::$app->request->post();

        foreach ($sections as $section) {
            foreach ($section->items as $item)
            {
                /* @var $item Item*/
                if ($item->load($post, $item['section']) && $item->validate()) {
                    if (isset($post[$item['section']][$item['key']])) {
                        try {
                            $item->setSetting($item['section'], $item['key'], $post[$item['section']][$item['key']]);
                            Yii::$app->session->setFlash('success', 'Success Message');
                            return $this->refresh('#'.strtolower($item['section']));
                        } catch (\DomainException $e) {
                            Yii::$app->errorHandler->logException($e);
                            Yii::$app->session->setFlash('error', $e->getMessage());
                            Yii::$app->session->setFlash('warning', 'Warning Message');
                        }
                    }
                }
            }
        }

        return $this->render('index', [
            'sections' => $sections
        ]);
    }

    /**
     * Example of action which intended to be used with ajax request.
     *
     * @return string
     */

    public function actionBackend()
    {
        $model = new BackendForm;
        $html = $this->renderPartial('frontend', [
            'model' => $model
        ]);
        return Json::encode($html);
    }
}
