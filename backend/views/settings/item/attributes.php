<?php

use common\widgets\DetailView;
use core\entities\settings\Section;
use yii2mod\settings\models\enumerables\SettingType;
use yii2mod\settings\models\enumerables\SettingStatus;

/* @var $setting yii2mod\settings\models\SettingModel */

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
        'attribute' => 'key',
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
    [
        'attribute' => 'value',
    ],
    [
        'attribute' => 'description',
    ],
    [
        'attribute' => 'status',
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
