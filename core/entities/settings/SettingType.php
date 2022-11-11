<?php

namespace core\entities\settings;

use common\widgets\DetailView;
use yii2mod\settings\models\enumerables\SettingType as BaseSettingType;

class SettingType extends BaseSettingType
{
    const PHONE_TYPE = 'phone';
    const EMAIL_TYPE = 'email';
    const URL_TYPE = 'url';
    const TEXTAREA = 'textarea';

    public static $list = [
        self::STRING_TYPE => 'String',      //DetailView::INPUT_TEXT
        self::INTEGER_TYPE => 'Integer',    //DetailView::INPUT_TEXT
        self::BOOLEAN_TYPE => 'Boolean',    //DetailView::INPUT_CHECKBOX
        self::FLOAT_TYPE => 'Float',        //DetailView::INPUT_TEXT
        self::NULL_TYPE => 'Null',          //No input
        self::PHONE_TYPE => 'Phone',        //DetailView::INPUT_TEXT
        self::EMAIL_TYPE => 'Email',        //DetailView::INPUT_TEXT
        self::URL_TYPE => 'URL',            //DetailView::INPUT_TEXT
        self::TEXTAREA => 'textarea',       //DetailView::INPUT_TEXTAREA
        //DetailView::INPUT_LIST_BOX
        //DetailView::INPUT_DROPDOWN_LIST or DetailView::INPUT_DEPDROP
        //DetailView::INPUT_CHECKBOX_LIST
        //DetailView::INPUT_RADIO_LIST
        //DetailView::INPUT_FILE
    ];

    public static function returnValueType(Setting $setting): string
    {
        switch ($setting->type) {
            case self::BOOLEAN_TYPE:
                return DetailView::INPUT_CHECKBOX;
                break;
            case self::INTEGER_TYPE:
                return DetailView::INPUT_LIST_BOX;
                break;
            default:
                return DetailView::INPUT_TEXT;
        }
    }

    public static function returnValueOptions(Setting $setting): array
    {
        switch ($setting->type) {
            case SettingType::BOOLEAN_TYPE:
                return [
                    'name' => '',
                    'options' => [
                        'label' => '<label class="has-star custom-control-label" for="setting-value"></label>',
                        'custom' => true,
                        'switch' => true
                    ]
                ];
                break;
            default:
                return [];
        }
    }
}
