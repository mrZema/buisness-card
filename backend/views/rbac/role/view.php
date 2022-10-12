<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\widgets\DetailView;
use core\entities\rbac\DefaultRoles;
use core\entities\rbac\AppPermissions;

/* @var $mode */
/* @var $this yii\web\View */
/* @var $roleEditForm backend\forms\rbac\RoleEditForm */

$this->title = $roleEditForm->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$back_btn = Html::a('<i class="fas fa-undo"></i>', 'index', ['class' => 'kv-action-btn']);
$update_btn = Yii::$app->user->can(AppPermissions::ROLES_EDIT) ? '{update} ' : '';
$delete_btn = Yii::$app->user->can(AppPermissions::ROLES_VIEW) && !DefaultRoles::isDefaultRole($roleEditForm->name) ? '{delete} ' : '';
$buttons1 = $update_btn . $delete_btn . $back_btn;
?>

<div class="role-view">
    <div class="box">
        <div class="box-body">
            <?= DetailView::widget(
                [
                    'model' => $roleEditForm,
                    'mode' => $mode,
                    'panel' => [
                        'type' => 'primary',
                        'heading' => '<i class="fas fa-user"></i> Role Details',
                        'footer' => '<div class="text-center text-muted">This is a sample footer message for the detail view.</div>'
                    ],
                    'buttons1' => $buttons1,
                    'deleteOptions' => [
                        'params' => [
                            'id' => $roleEditForm->name,
                            'kvdelete' => true
                        ],
                        'url' => Url::toRoute(['delete', 'name' => $roleEditForm->name])
                    ],
                    'attributes' => [
                        [
                            'attribute' => 'name',
                            'displayOnly' => DefaultRoles::isDefaultRole($roleEditForm->name) ?? false
                        ],
                        'description:ntext',
                        [
                            'attribute' => 'child_roles',
                            'value' => implode(', ', $roleEditForm->child_roles),
                            'format' => 'raw',
                            'type' => DetailView::INPUT_SELECT2,
                            'widgetOptions' => [
                                'data' => $roleEditForm->returnRoleList(),
                                'options' => ['value' => array_keys($roleEditForm->child_roles), 'placeholder' => 'Select ...', 'multiple' => true],
                                'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                            ],
                        ],
                        [
                            'attribute' => 'permissions',
                            'value' => implode(', ', $roleEditForm->permissions),
                            'format' => 'raw',
                            'type' => DetailView::INPUT_SELECT2,
                            'widgetOptions' => [
                                'data' => $roleEditForm->returnPermissionList(),
                                'options' => ['value' => array_keys($roleEditForm->permissions), 'placeholder' => 'Select ...', 'multiple' => true],
                                'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                            ],
                        ],
                        [
                            'attribute' => 'ruleName',
                            'visible' => Yii::$app->user->can(DefaultRoles::DEV) ?? false
                        ],

                    ],
                    'template' => '<tr><th style="width:25%">{label}</th><td>{value}</td></tr>',
                ]);
            ?>
        </div>
    </div>
</div>
