<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use core\entities\rbac\DefaultRoles;
use core\entities\rbac\AppPermissions;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\AuthItem */

$settings = Yii::$app->settings;

$this->title = Yii::t('rbac-admin', 'Roles');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="role-index">
    <p>
        <? if ($settings->get('BackEnd', 'CreateRoleAllowance')) echo Html::a(Yii::t('rbac-admin', 'Create ' . $labels['Item']), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'name',
                'label' => Yii::t('rbac-admin', 'Name'),
            ],
            [
                'attribute' => 'description',
                'label' => Yii::t('rbac-admin', 'Description'),
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'visibleButtons' => [
                    'update' => Yii::$app->user->can(AppPermissions::ROLES_EDIT),
                    'delete' => function ($model) {
                        return Yii::$app->user->can(AppPermissions::ROLES_EDIT) && !DefaultRoles::isDefaultRole($model->name);
                    }
                ],
                'urlCreator' => function ($action, $model) {
                    switch ($action) {
                        case 'update':
                            return 'view?name=' . $model->name . '&mode=edit';
                            break;
                        case 'delete':
                            return 'delete?name=' . $model->name;
                            break;
                        default:
                            return 'view?name=' . $model->name;
                    }
                }
            ],
        ],
    ])
    ?>

</div>
