<?php

namespace backend\forms\settings;

use yii\base\Model;
use core\entities\settings\Section;

class SectionForm extends Model
{
    public $name;
    public $description;
    public $status;

    private $_section;

    public function __construct(Section $section = null, $config = [])
    {
        if ($section) {
            $this->name = $section->name;
            $this->description = $section->description;
            $this->status = $section->status;
            $this->_section = $section;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'status'], 'required'],
            [['name'], 'string', 'max' => 30],
            [['description'], 'string', 'max' => 255],
            [['status'], 'integer', 'min' => 0, 'max' => 10],
            [['name'], 'unique', 'targetClass' => Section::class, 'filter' => $this->_section ? ['<>', 'name', $this->_section->name] : null]
        ];
    }
}
