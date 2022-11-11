<?php
namespace core\entities\settings;

use yii\db\ActiveQuery;
use yii2mod\settings\models\SettingModel;
use yii2mod\settings\models\enumerables\SettingStatus;

class Setting extends SettingModel
{
    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['section', 'key', 'value'], 'required'],
            [['section', 'key'], 'unique', 'targetAttribute' => ['section', 'key']],
            [['value', 'type'], 'string'],
            [['section', 'key', 'description'], 'string', 'max' => 255],
            [['status'], 'integer'],
            ['status', 'default', 'value' => SettingStatus::ACTIVE],
            ['status', 'in', 'range' => SettingStatus::getConstantsByName()],
            $this->returnValueRule(),
            [['type'], 'safe'],
        ];
    }

    protected function returnValueRule(): array
    {
        switch ($this->type) {
            case SettingType::BOOLEAN_TYPE:
                $value_rule = ['value','boolean']; break;
            case SettingType::EMAIL_TYPE:
                $value_rule = ['value','email']; break;
            case SettingType::URL_TYPE:
                $value_rule = ['value','url', 'defaultScheme' => 'http']; break;
            default:
                $value_rule = ['value', 'string', 'max' => 16]; break;
        }
        return $value_rule;
    }

    /**
     * Returns associated Section ActiveQuery.
     *
     * It's surplus method so far, is not used anywhere.
     *
     * @return ActiveQuery
     */
    public function getSection()
    {
        return $this->hasOne(Section::class, ['name' => 'section']);
    }
}
