<?php

namespace core\helpers;

use Yii;
use Exception;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use core\entities\loggers\ErrorRecord;

class ErrorLevelHelper
{
    public static function levelList(): array
    {
        return [
            ErrorRecord::LEVEL_ERROR => 'Error',
            ErrorRecord::LEVEL_WARNING => 'Warning',
            ErrorRecord::LEVEL_INFO => 'Info',
        ];
    }

    /**
     * @param $status
     * @return string
     */
    public static function levelLabel($status): string
    {
        switch ($status) {
            case ErrorRecord::LEVEL_INFO:
                $class = 'badge badge-default';
                break;
            case ErrorRecord::LEVEL_ERROR:
                $class = 'badge badge-danger';
                break;
            case ErrorRecord::LEVEL_WARNING:
                $class = 'badge badge-warning';
                break;
            default:
                $class = 'badge badge-default';
        }

        try {
            $content = ArrayHelper::getValue(self::levelList(), $status);
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
            $content = 'Unknown';
        }

        return Html::tag('span', $content, ['class' => $class]);
    }
}
