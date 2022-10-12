<?php

use yii\web\View;
use common\widgets\DetailView;
use core\entities\settings\Section;
use yii2mod\settings\models\SettingModel;
use yii2mod\settings\models\enumerables\SettingType;
use yii2mod\settings\models\enumerables\SettingStatus;

/* @var $this View */
/* @var $setting SettingModel */

$this->title = Yii::t('yii2mod.settings', 'Create Setting');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii2mod.settings', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="setting-create">
    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $setting,
                'mode' => 'edit',
                'panel' => [
                    'type' => 'primary',
                    'heading' => '<i class="fas fa-user"></i> Setting Details',
                    'footer' => '<div class="text-center text-muted">This is a sample footer message for the detail view.</div>'
                ],
                'buttons2' => '{save}',
                'attributes' => require __DIR__ . '/attributes.php'
            ]) ?>
        </div>
    </div>
</div>
