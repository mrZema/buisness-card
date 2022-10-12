<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use core\helpers\UserHelper;
use common\widgets\DetailView;
use core\entities\rbac\AppPermissions;

/* @var $this yii\web\View */
/* @var $user core\entities\auth\User */
/* @var $userEditForm backend\forms\user\UserEditForm */
/* @var $mode */

$this->title = 'User: ' . $user->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$back_btn = Html::a('<i class="fas fa-undo"></i>', 'index', ['class' => 'kv-action-btn']);
$update_btn = Yii::$app->user->can(AppPermissions::OTHER_USER_EDIT) ? '{update} ' : '';
$delete_btn = Yii::$app->user->can(AppPermissions::OTHER_USER_DELETE) ? '{delete} ' : '';
$buttons1 = $update_btn . $delete_btn . $back_btn;
?>

<div class="user-view">
    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $user,
                'form' => $userEditForm,
                'mode' => $mode,
                'panel' => [
                    'type' => 'primary',
                    'heading' => '<i class="fas fa-user"></i> User Details',
                    'footer' => '<div class="text-center text-muted">This is a sample footer message for the detail view.</div>'
                ],
                'buttons1' => $buttons1,
                'attributes' => [
                    [
                        'attribute' => 'id',
                        'displayOnly' => true
                    ],
                    [
                        'attribute' => 'username',
                        'displayOnly' => Yii::$app->user->id === $user->id ? false : true
                    ],
                    [
                        'attribute' => 'email',
                        'displayOnly' => !Yii::$app->user->can(AppPermissions::OTHER_USER_EMAIL_CHANGE),
                    ],
                    [
                        'attribute' => 'status',
                        'value' => UserHelper::statusLabel($user->status),
                        'displayOnly' => !Yii::$app->user->can(AppPermissions::OTHER_USER_STATUS_CHANGE),
                        'format' => 'raw',
                        'type' => DetailView::INPUT_SELECT2,
                        'widgetOptions' => [
                            'data' => UserHelper::statusList(),
                            'options' => ['value' => $user->status, 'placeholder' => 'Select ...'],
                            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                        ],
                    ],
                    [
                        'attribute' => 'roles',
                        'value' => implode(', ', $userEditForm->roles),
                        'displayOnly' => !Yii::$app->user->can(AppPermissions::OTHER_USER_ROLE_CHANGE),
                        'format' => 'raw',
                        'type' => DetailView::INPUT_SELECT2,
                        'widgetOptions' => [
                            'data' => ArrayHelper::map(Yii::$app->authManager->getRoles(),'name', 'name'),
                            'options' => ['value' => array_keys($userEditForm->roles), 'placeholder' => 'Select ...', 'multiple' => true],
                            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                        ],
                    ],
                    [
                        'attribute' => 'created_at',
                        'displayOnly' => true,
                        'value' => date('H:i d.m.Y', $user->created_at)
                    ],
                    [
                        'attribute' => 'updated_at',
                        'displayOnly' => true,
                        'value' => date('H:i d.m.Y', $user->updated_at)
                    ]
                ],
            ]) ?>
        </div>
    </div>
</div>
