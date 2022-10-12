<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use core\helpers\SectionHelper;
use core\entities\settings\Section;

/* @var $this yii\web\View */
/* @var $searchModel core\search\settings\SectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sections';
$this->params['breadcrumbs'][] = 'Settings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="section-index">

    <p><?= Html::a('Create Section', ['create'], ['class' => 'btn btn-success']) ?></p>

    <?php Pjax::begin(); ?>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                    ],
                    [
                        'attribute' => 'name',
                        'value' => function (Section $model) {
                            return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => SectionHelper::statusList(),
                        'value' => function (Section $model) {
                            return SectionHelper::statusLabel($model->status);
                        },
                        'format' => 'raw',
                    ],
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
            ]); ?>
        </div>
    </div>

    <?php Pjax::end(); ?>

</div>
