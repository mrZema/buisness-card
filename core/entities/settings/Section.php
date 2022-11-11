<?php

namespace core\entities\settings;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * @property integer id
 * @property string $name
 * @property integer $status
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 */

class Section extends ActiveRecord
{
    const STATUS_DRAFT = 0;
    const STATUS_DISABLED = 1;
    const STATUS_ACTIVE = 10;

    public static function create($name, $status, $description): self
    {
        $section = new static();
        $section->name = $name;
        $section->status = $status;
        $section->description = $description;
        $section->created_at = time();
        $section->updated_at = time();
        return $section;
    }

    public function edit($name, $status, $description): void
    {
        $this->name = $name;
        $this->status = $status;
        $this->description = $description;
        $this->updated_at = time();
    }

    public function getItems()
    {
        return $this->hasMany(Setting::class, ['section' => 'name']);
    }

    public static function returnList(): array
    {
        return ArrayHelper::map(Section::find()->orderBy('name')->asArray()->all(), 'name', 'name');
    }

    public static function tableName(): string
    {
        return '{{%setting_sections}}';
    }
}
