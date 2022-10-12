<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use core\entities\settings\Section;

/* @var $this yii\web\View */
/* @var $sections Section[] */
/* @var $section_name string */

foreach ($sections[$section_name]->items as $item) {
    $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'options' => ['class' => 'row m-2'],
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-4',
                'wrapper' => 'col-sm-8'
            ],
        ],
    ]);
    echo $form->field($item, 'value', ['options' => ['class' => 'row col-sm-11']])
        ->textInput([
            'id' => $item['section'] . '-' . $item['key'],
            'name' => $item['section'] . '[' . $item['key'] . ']'
        ])
        ->label($item['key'] . ':');
    echo '<div class="col-sm-1 mt-1">' . Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-sm btn-success']) . '</div>';
    ActiveForm::end();
}
