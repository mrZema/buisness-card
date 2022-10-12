<?php
namespace backend\controllers;

use core\entities\rbac\DefaultRoles;
use yii\web\Controller;
use yii\filters\AccessControl;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'layout' => 'blank',
            ],
        ];
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'except' => ['index', 'error'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['developer'],
                        'roles' => [DefaultRoles::DEV],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['owner'],
                        'roles' => ['owner'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionDeveloper()
    {
        return $this->asJson('You have access!');
    }

    public function actionOwner()
    {
        return $this->asJson('You have access!');
    }
}
