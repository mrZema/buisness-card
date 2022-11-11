<?php

use common\widgets\DetailView;
use core\entities\settings\Section;
use core\entities\settings\Setting;
use core\entities\settings\SettingType;
use yii2mod\settings\models\enumerables\SettingStatus;

/* @var $setting Setting */

$field_visibility = $setting->type ? true : false;

return [
    [
        'attribute' => 'section',
        'value' => $setting->section,
        'format' => 'raw',
        'type' => DetailView::INPUT_SELECT2,
        'widgetOptions' => [
            'data' => Section::returnList(),
            'options' => ['value' => $setting->section, 'placeholder' => 'Select ...'],
            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
        ],
    ],
    [
        'attribute' => 'key'
    ],
    [
        'attribute' => 'type',
        'value' => $setting->type,
        'format' => 'raw',
        'type' => DetailView::INPUT_SELECT2,
        'widgetOptions' => [
            'data' => SettingType::listData(),
            'options' => ['value' => $setting->type, 'placeholder' => 'Select ...'],
            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
        ],
    ],
    array_merge(
        [
            'attribute' => 'value',
            'visible' => $field_visibility,
            'value' => $setting->value,
            'format' => 'raw',
            'type' => SettingType::returnValueType($setting),
        ],
        SettingType::returnValueOptions($setting)
    ),
    [
        'attribute' => 'description',
        'visible' => $field_visibility,
    ],
    [
        'attribute' => 'status',
        'visible' => $field_visibility,
        'value' => $setting->status,
        'format' => 'raw',
        'type' => DetailView::INPUT_SELECT2,
        'widgetOptions' => [
            'data' => SettingStatus::listData(),
            'options' => [
                'value' => $setting->status ?? SettingStatus::ACTIVE,
                'placeholder' => 'Select ...'
            ],
            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
        ],
    ],
];
