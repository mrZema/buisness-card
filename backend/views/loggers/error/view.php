<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\widgets\DetailView;
use core\helpers\DataFormatHelper;
use core\helpers\ErrorLevelHelper;

/* @var $this yii\web\View */
/* @var $error core\entities\loggers\ErrorRecord; */

$this->title = $error->category;
$this->params['breadcrumbs'][] = ['label' => 'Errors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$back_btn = Html::a('<i class="fas fa-undo"></i>', 'index', ['class' => 'kv-action-btn']);
$buttons1 = '{delete}' . $back_btn;
?>
<div class="error-view">

    <div class="box">
        <div class="box-body">
            <? try {
                echo DetailView::widget([
                    'model' => $error,
                    'panel' => [
                        'type' => 'primary',
                        'heading' => '<i class="fas fa-times"></i> Error Details',
                        'footer' => '<div class="text-center text-muted">This is a sample footer message for the detail view.</div>'
                    ],
                    'buttons1' => $buttons1,
                    'deleteOptions' => [
                        'params' => [
                            'id' => $error->id,
                            'kvdelete' => true
                        ],
                        'url' => Url::toRoute(['delete', 'id' => $error->id])
                    ],
                    'attributes' => [
                        'category',
                        [
                            'attribute' => 'level',
                            'value' => ErrorLevelHelper::levelLabel($error->level),
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'log_time',
                            'value' => DataFormatHelper::returnDateWithMilliseconds($error->log_time),
                        ],
                        'ip',
                        'user_id',
                        'session_id',
                        [
                            'attribute' => 'message',
                            'format' => 'html',
                            'value' => nl2br($error->message),
                        ],
                        [
                            'attribute' => 'sys_info',
                            'format' => 'html',
                            'value' => nl2br($error->sys_info),
                        ]
                    ],
                ]);
            } catch (Exception $e) {
                echo 'Detail widget should be here, but something went wrong. </br> 
                        We already got error report and started work under this issue. </br>
                        If it will not be fixed after sometime please message to support. </br>
                        Except apologises for inconvenience';
                Yii::$app->errorHandler->logException($e);
            } ?>
        </div>
    </div>
</div>
