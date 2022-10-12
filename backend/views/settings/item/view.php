<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\widgets\DetailView;

/* @var $mode */
/* @var $this yii\web\View */
/* @var $setting yii2mod\settings\models\SettingModel */

$this->title = 'Setting: ' . $setting->key;
$this->params['breadcrumbs'][] = ['label' => 'Setting', 'url' => ['index']];
$this->params['breadcrumbs'][] = $setting->key;
?>

<div class="setting-view">
    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $setting,
                'mode' => $mode,
                'panel' => [
                    'type' => 'primary',
                    'heading' => '<i class="fas fa-user"></i> Setting Details',
                    'footer' => '<div class="text-center text-muted">This is a sample footer message for the detail view.</div>'
                ],
                'buttons1' => Html::a('<i class="fas fa-undo"></i>', 'index', ['class' => 'kv-action-btn']) . ' {update} {delete}',
                'deleteOptions' => [
                    'params' => [
                        'id' => $setting->id,
                        'kvdelete' => true
                    ],
                    'url' => Url::toRoute(['delete', 'id' => $setting->id])
                ],
                'attributes' => ArrayHelper::merge(
                    require __DIR__ . '/attributes.php',
                    [
                        [
                            'attribute' => 'created_at',
                            'displayOnly' => true,
                            'value' => date('H:i d.m.Y', $setting->created_at)
                        ],
                        [
                            'attribute' => 'updated_at',
                            'displayOnly' => true,
                            'value' => date('H:i d.m.Y', $setting->updated_at)
                        ],
                    ]
                )
            ]) ?>
        </div>
    </div>
</div>
