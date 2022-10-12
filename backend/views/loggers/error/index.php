<?php

use yii\bootstrap4\Html;
use kartik\grid\GridView;
use kartik\dialog\Dialog;
use kartik\date\DatePicker;
use common\assets\GridAsset;
use kartik\grid\ActionColumn;
use core\helpers\ErrorLevelHelper;
use core\helpers\DataFormatHelper;
use core\entities\loggers\ErrorRecord;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $searchModel core\search\logger\ErrorLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

GridAsset::register($this);

$this->title = 'Error log';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="error-list">
    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'id' => 'grid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'pjax' => true,
                'panel' => [
                    'type' => GridView::TYPE_PRIMARY,
                    'heading' => '<i class="fas fa-times"></i> Error reports',
                ],
                'toolbar' => [
                    [
                        'content' =>
                            Html::a('<i class="fas fa-redo"></i>', ['index'], [
                                'class' => 'btn btn-outline-secondary',
                                'title' => Yii::t('kvgrid', 'Reset Grid')
                            ]) . ' ' .
                            Html::button('<i class="fas fa-trash-alt"></i>', [
                                'id' => 'btnDeleteGroup',
                                'class' => 'btn btn-outline-danger',
                                'title' => Yii::t('kvgrid', 'Delete All Checked Errors'),
                                'onclick' => 'deleteGroup()'
                            ]),
                        'options' => ['class' => 'btn-group mr-2']
                    ],
                    '{export}',
                ],
                'resizableColumns' => false,
                'columns' => [
                    [
                        'class' => 'kartik\grid\CheckboxColumn',
                        'headerOptions' => ['class' => 'kartik-sheet-style'],
                    ],
                    [
                        'attribute' => 'log_time',
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'date_from',
                            'attribute2' => 'date_to',
                            'options' => ['placeholder' => 'Start date'],
                            'options2' => ['placeholder' => 'End date'],
                            'type' => DatePicker::TYPE_RANGE,
                            'separator' => '-',
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'autoclose' => true,
                                'format' => 'dd.mm.yyyy',
                            ],
                        ]),
                        'value' => function (ErrorRecord $model) {
                            return DataFormatHelper::returnDateWithMilliseconds($model->log_time);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'level',
                        'filter' => ErrorLevelHelper::levelList(),
                        'value' => function (ErrorRecord $model) {
                            return ErrorLevelHelper::levelLabel($model->level);
                        },
                        'format' => 'raw',
                    ],
                    'category',
                    'ip',
                    'user_id',
                    [
                        'class' => ActionColumn::class,
                        'template' => '{view} {delete}',
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

<?
echo Dialog::widget([
    'libName' => 'noOneChosen',
    'options' => [
        'type' => Dialog::TYPE_WARNING,
        'title' => 'Warning',
        'message' => 'No one error report chosen for deletion.',
    ]
]);
?>
<?php


echo Dialog::widget([
    'libName' => 'deleteConfirmation',
    'options' => [
        'type' => Dialog::TYPE_WARNING,
        'title' => 'Warning',
        'message' => 'This will delete {number} Error report(s) from log.\n\nAre you sure?'
    ]
]);
?>
