<?php

use yii\web\View;
use common\widgets\DetailView;
use core\helpers\SectionHelper;
use core\entities\settings\Section;
use backend\forms\settings\SectionForm;

/* @var $this View */
/* @var $sectionForm SectionForm */

$this->title = Yii::t('yii2mod.settings', 'Create Section');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii2mod.settings', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="section-create">
    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $sectionForm,
                'mode' => 'edit',
                'panel' => [
                    'type' => 'primary',
                    'heading' => '<i class="fas fa-user"></i> Section Details',
                    'footer' => '<div class="text-center text-muted">This is a sample footer message for the detail view.</div>'
                ],
                'buttons2' => '{save}',
                'attributes' => [
                    [
                        'attribute' => 'name',
                    ],
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'type' => DetailView::INPUT_SELECT2,
                        'widgetOptions' => [
                            'data' => SectionHelper::statusList(),
                            'options' => ['value' => Section::STATUS_ACTIVE, 'placeholder' => 'Select status...'],
                            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                        ],
                    ],
                    [
                        'attribute' => 'description',
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
