<?php

namespace core\helpers;

use Exception;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use core\entities\settings\Section;

class SectionHelper
{
    public static function statusList(): array
    {
        return [
            Section::STATUS_DRAFT => 'Draft',
            Section::STATUS_DISABLED => 'Disabled',
            Section::STATUS_ACTIVE => 'Active',
        ];
    }

    /**
     * @param $status
     * @return string
     * @throws Exception
     */

    public static function statusLabel($status): string
    {
        switch ($status) {
            case Section::STATUS_DRAFT:
                $class = 'badge badge-default';
                break;
            case Section::STATUS_DISABLED:
                $class = 'badge badge-warning';
                break;
            case Section::STATUS_ACTIVE:
                $class = 'badge badge-success';
                break;
            default:
                $class = 'badge badge-default';
        }

        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }
}
