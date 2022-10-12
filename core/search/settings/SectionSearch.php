<?php

namespace core\search\settings;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\entities\settings\Section;

class SectionSearch extends Model
{
    public $name;
    public $status;

    public function rules(): array
    {
        return [
            [['name'], 'safe'],
            [['status'], 'integer'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Section::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['name' => SORT_ASC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
