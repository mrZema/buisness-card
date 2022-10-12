<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $roleCreateForm backend\forms\rbac\RoleCreateForm */

$this->title = Yii::t('rbac-admin', 'Create ' . $labels['Item']);
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', $labels['Items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$roles = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description');

$back_btn = Html::a('<i class="fas fa-undo"></i>', 'index', ['class' => 'kv-action-btn']);
$buttons2 = '{save} '. $back_btn;
?>
<div class="role-create">
    <div class="box">
        <div class="box-body">
            <?= DetailView::widget(
                [
                    'model' => $roleCreateForm,
                    'mode' => 'edit',
                    'panel' => [
                        'type' => 'primary',
                        'heading' => '<i class="fas fa-user"></i> Role Details',
                        'footer' => '<div class="text-center text-muted">This is a sample footer message for the detail view.</div>'
                    ],
                    'buttons2' => $buttons2,
                    'attributes' => [
                        'name',
                        'description:ntext',
                        [
                            'attribute' => 'child_roles',
                            'format' => 'raw',
                            'type' => DetailView::INPUT_SELECT2,
                            'widgetOptions' => [
                                'data' => $roles,
                                'options' => ['placeholder' => 'Select ...', 'multiple' => true],
                                'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                            ],
                        ],
                        [
                            'attribute' => 'permissions',
                            'format' => 'raw',
                            'type' => DetailView::INPUT_SELECT2,
                            'widgetOptions' => [
                                'data' => ArrayHelper::map(Yii::$app->authManager->getPermissions(), 'name', 'name'),
                                'options' => ['placeholder' => 'Select ...', 'multiple' => true],
                                'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                            ],
                        ],
                        'ruleName'
                    ],
                    'template' => '<tr><th style="width:25%">{label}</th><td>{value}</td></tr>',
                ]);
            ?>
        </div>
    </div>
</div>

