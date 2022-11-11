<?php

use yii\web\View;
use yii\widgets\Pjax;
use common\widgets\DetailView;
use core\entities\settings\Setting;

/* @var $this View */
/* @var $setting Setting */

$this->title = Yii::t('yii2mod.settings', 'Create Setting');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii2mod.settings', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('@web/js/settings/create_v0.0.1.js', ['type' => 'module']);
?>

<div class="setting-create">
    <div class="box">
        <div class="box-body">
            <?php Pjax::begin(['id' => 'form-container'])?>
            <?= DetailView::widget([
                'model' => $setting,
                'mode' => 'edit',
                'panel' => [
                    'type' => 'primary',
                    'heading' => '<i class="fas fa-user"></i> Setting Details',
                    'footer' => '<div class="text-center text-muted">This is a sample footer message for the detail view.</div>'
                ],
                'buttons2' => '{save}',
                'attributes' => require __DIR__ . '/attributes.php',
                'formOptions' => [
                    'id' => 'setting-form'
                ]
            ]) ?>
            <?php Pjax::end()?>
        </div>
    </div>
</div>
