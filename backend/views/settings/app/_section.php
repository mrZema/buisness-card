<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\form\ActiveField;
use yii\helpers\BaseInflector;
use core\entities\settings\Setting;
use core\entities\settings\Section;
use yii2mod\settings\models\enumerables\SettingType;

/* @var $this yii\web\View */
/* @var $item Setting */
/* @var $sections Section[] */
/* @var $section_name string */

foreach ($sections[$section_name]->items as $item) {
    $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'options' => ['class' => 'row mb-2 mt-2'],
        'formConfig' => [
            'labelSpan' => 4,
        ]
    ]);

    $field_params = [
        'options' => ['class' => 'row col-sm-12'],
        'hintType' => ActiveField::HINT_SPECIAL,
        'hintSettings' => ['showIcon' => false],
    ];
    $field_options = [
        'id' => $item['section'] . '-' . $item['key'],
        'name' => $item['section'] . '[' . $item['key'] . ']'
    ];
    if ($item['type'] == SettingType::BOOLEAN_TYPE) {
        echo $form->field($item, 'value', array_merge([
            'autoOffset' => false,
            'contentAfterLabel' => Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-sm btn-success float-right mr-3'])
        ], $field_params))
            ->checkbox(array_merge([
                'custom' => true,
                'switch' => true,
            ], $field_options))
            ->label(BaseInflector::titleize($item['key']) . ':')
            ->hint($item['description']);
    } else {
        echo $form->field($item, 'value', array_merge([
            'addon' => [
                'append' => [
                    'content' => Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-sm btn-success']),
                    'asButton' => true
                ]]
        ], $field_params))
            ->textInput($field_options)
            ->label(BaseInflector::titleize($item['key']) . ':')
            ->hint($item['description']);
    }
    ActiveForm::end();
}
