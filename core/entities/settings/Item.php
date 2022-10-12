<?php
namespace core\entities\settings;

use yii\db\ActiveQuery;
use yii2mod\settings\models\SettingModel;

class Item extends SettingModel
{
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
