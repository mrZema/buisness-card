<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\widgets\DetailView;
use core\helpers\SectionHelper;

/* @var $mode */
/* @var $this yii\web\View */
/* @var $section  core\entities\settings\Section */
/* @var $sectionForm backend\forms\settings\SectionForm; */

$this->title = 'Section: ' . $section->name;
$this->params['breadcrumbs'][] = 'Settings';
$this->params['breadcrumbs'][] = ['label' => 'Section', 'url' => ['index']];
$this->params['breadcrumbs'][] = $section->name;
?>

<div class="section-view">
    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $section,
                'form' => $sectionForm,
                'mode' => $mode,
                'panel' => [
                    'type' => 'primary',
                    'heading' => '<i class="fas fa-user"></i> Section Details',
                    'footer' => '<div class="text-center text-muted">This is a sample footer message for the detail view.</div>'
                ],
                'buttons1' => Html::a('<i class="fas fa-undo"></i>', 'index', ['class' => 'kv-action-btn']) . ' {update} {delete}',
                'deleteOptions' => [
                    'params' => [
                        'id' => $section->id,
                        'kvdelete' => true
                    ],
                    'url' => Url::toRoute(['delete', 'id' => $section->id])
                ],
                'attributes' => [
                    [
                        'attribute' => 'name',
                    ],
                    [
                        'attribute' => 'status',
                        'value' => SectionHelper::statusLabel($section->status),
                        'format' => 'raw',
                        'type' => DetailView::INPUT_SELECT2,
                        'widgetOptions' => [
                            'data' => SectionHelper::statusList(),
                            'options' => ['value' => $section->status, 'placeholder' => 'Select ...'],
                            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                        ],
                    ],
                    [
                        'attribute' => 'description',
                    ],
                    [
                        'attribute' => 'created_at',
                        'displayOnly' => true,
                        'value' => date('H:i d.m.Y', $section->created_at)
                    ],
                    [
                        'attribute' => 'updated_at',
                        'displayOnly' => true,
                        'value' => date('H:i d.m.Y', $section->updated_at)
                    ]
                ],
            ]) ?>
        </div>
    </div>
</div>
