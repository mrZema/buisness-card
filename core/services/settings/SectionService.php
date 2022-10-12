<?php

namespace core\services\settings;

use Yii;
use RuntimeException;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use core\entities\settings\Section;
use backend\forms\settings\SectionForm;
use yii2mod\settings\components\Settings;
use core\repositories\settings\SectionRepository;

class SectionService
{
    private $sections;

    public function __construct(SectionRepository $sections)
    {
        $this->sections = $sections;
    }

    /**
     * Creates Section model.
     *
     * @param SectionForm $form
     * @return Section
     * @throws RuntimeException
     */
    public function create(SectionForm $form): Section
    {
        $section = Section::create(
            $form->name,
            $form->status,
            $form->description
        );
        $this->sections->save($section);
        return $section;
    }

    /**
     * Edits and saves Section model.
     *
     * @param $id
     * @param SectionForm $form
     * @throws NotFoundHttpException
     * @throws RuntimeException
     */
    public function edit($id, SectionForm $form): void
    {
        $section = $this->sections->get($id);
        $section->edit(
            $form->name,
            $form->status,
            $form->description
        );
        $this->sections->save($section);
    }

    /**
     * Removes Section model from DB.
     *
     * @param $id
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws StaleObjectException
     * @throws RuntimeException
     */
    public function remove($id): void
    {
        $section = $this->sections->get($id);
        $this->sections->remove($section);
    }

    /**
     * Answers the question: "Does particular section have at least one attached item?"
     *
     * @param $id
     * @return bool
     * @throws NotFoundHttpException
     */
    public function hasChildren($id): bool
    {
        $section = $this->sections->get($id);
        $items = $section->getItems()->all();
        return !empty($items);
    }

    /**
     * Finds a Section model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     * @return Section|null the loaded model
     * @throws NotFoundHttpException
     */
    public function find(int $id): Section
    {
        return $this->sections->get($id);
    }

    /**
     * Returns array of All Active Sections. Array can be empty.
     *
     * @return array[]
     */
    public function findAllActive(): array
    {
        return $this->sections->getAllActive();
    }
}
