<?php

namespace core\repositories\settings;

use RuntimeException;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use core\entities\settings\Section;
use yii2mod\settings\models\enumerables\SettingStatus;

class SectionRepository
{
    /**
     * Returns Section model by id.
     *
     * @param $id
     * @return Section
     * @throws NotFoundHttpException
     */
    public function get($id): Section
    {
        if (!$section = Section::findOne($id)) {
            throw new NotFoundHttpException('Section is not found.');
        }
        return $section;
    }

    /**
     * Returns array of All Active Sections, with all active items.
     *
     * @return array[] Array can be empty.
     */
    public function getAllActive(): array
    {
        return $allActiveSections = Section::find()
            ->where(['status' => Section::STATUS_ACTIVE])
            ->with([
                'items' => function ($query) {
                    $query->andWhere(['status' => SettingStatus::ACTIVE]);
                },
            ])
            ->all();
    }

    /**
     * Saves Section.
     *
     * @param Section $section
     *
     * @throws RuntimeException
     */
    public function save(Section $section): void
    {
        if (!$section->save()) {
            throw new RuntimeException('Saving error.');
        }
    }

    /**
     * Removes Section.
     *
     * @param Section $section
     * @throws \Throwable
     * @throws StaleObjectException
     * @throws RuntimeException
     */
    public function remove(Section $section): void
    {
        if (!$section->delete()) {
            throw new RuntimeException('Removing error.');
        }
    }
}
