<?php

namespace core\entities\loggers;

use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $level
 * @property string $category
 * @property double $log_time
 * @property string $ip
 * @property integer $user_id
 * @property string $session_id
 * @property string $message
 * @property string $sys_info
 */

class ErrorRecord extends ActiveRecord
{
    const LEVEL_ERROR = 1;
    const LEVEL_WARNING = 2;
    const LEVEL_INFO = 4;

    public static function tableName(): string
    {
        return '{{%log_errors}}';
    }
}
