<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii2mod\editable\EditableColumn;
use yii2mod\settings\models\SettingModel;
use yii2mod\settings\models\enumerables\SettingStatus;
use yii2mod\settings\models\enumerables\SettingType;
use yii2mod\settings\models\search\SettingSearch;

/* @var $dataProvider ActiveDataProvider */
/* @var $searchModel SettingSearch */

$this->title = Yii::t('yii2mod.settings', 'Settings');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="setting-index">
    <p><?php echo Html::a(Yii::t('yii2mod.settings', 'Create Setting'), ['create'], ['class' => 'btn btn-success']); ?></p>

    <?php Pjax::begin(['timeout' => 10000, 'enablePushState' => false]); ?>

    <?php echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                ],
                [
                    'attribute' => 'type',
                    'filter' => SettingType::listData(),
                    'filterInputOptions' => ['prompt' => Yii::t('yii2mod.settings', 'Select Type'), 'class' => 'form-control'],
                ],
                [
                    'attribute' => 'section',
                    'filter' => ArrayHelper::map(SettingModel::find()->select('section')->distinct()->all(), 'section', 'section'),
                    'filterInputOptions' => ['prompt' => Yii::t('yii2mod.settings', 'Select Section'), 'class' => 'form-control'],
                ],
                'key',
                'value:ntext',
                [//ToDo Solve EditableColumn problem
                    'class' => EditableColumn::class,
                    'attribute' => 'status',
                    'url' => ['edit-setting'],
                    'value' => function ($model) {
                        return SettingStatus::getLabel($model->status);
                    },
                    'type' => 'select',
                    'editableOptions' => function ($model) {
                        return [
                            'source' => SettingStatus::listData(),
                            'value' => $model->status,
                        ];
                    },
                    'filter' => SettingStatus::listData(),
                    'filterInputOptions' => ['prompt' => Yii::t('yii2mod.settings', 'Select Status'), 'class' => 'form-control'],
                ],
                'description:ntext',
                [
                    'header' => Yii::t('yii2mod.settings', 'Actions'),
                    'class' => 'kartik\grid\ActionColumn',
                    'urlCreator' => function ($action, $model) {
                        switch ($action) {
                            case 'update': return 'view?id='.$model->id.'&mode=edit'; break;
                            case 'delete': return 'delete?id='.$model->id; break;
                            default: return 'view?id='.$model->id;
                        }
                    }
                ],
            ],
        ]
    ); ?>
    <?php Pjax::end(); ?>
</div>
