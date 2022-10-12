<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $userCreateForm backend\forms\user\UserCreateForm */


$this->title = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$back_btn = Html::a('<i class="fas fa-undo"></i>', 'index', ['class' => 'kv-action-btn']);
$buttons2 = '{save} '. $back_btn;
?>

<div class="user-create">
    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $userCreateForm,
                'mode' => DetailView::MODE_EDIT,
                'panel' => [
                    'type' => 'primary',
                    'heading' => '<i class="fas fa-user"></i> User Details',
                    'footer' => '<div class="text-center text-muted">This is a sample footer message for the detail view.</div>'
                ],
                'buttons2' => $buttons2,
                'attributes' => [
                    'username:text',
                    'email:email',
                    [
                        'attribute' => 'roles',
                        'value' => 'User',
                        'format' => 'raw',
                        'type' => DetailView::INPUT_SELECT2,
                        'widgetOptions' => [
                            'data' => ArrayHelper::map(ArrayHelper::toArray(Yii::$app->authManager->getRoles()),'name', 'description'),
                            'options' => ['value' => DefaultRoles::USER, 'placeholder' => 'Select ...', 'multiple' => true],
                            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                        ],
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
