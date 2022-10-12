<?php

namespace core\search\logger;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\entities\loggers\ErrorRecord;

class ErrorLogSearch extends Model
{
    public $date_from;
    public $date_to;
    public $ip;
    public $user_id;
    public $category;
    public $level;

    public function rules(): array
    {
        return [
            [['level'], 'integer'],
            [['category', 'ip', 'user_id'], 'string'],
            [['date_from', 'date_to'], 'date', 'format' => 'php:d.m.Y'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = ErrorRecord::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['log_time' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['>=', 'log_time', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'log_time', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null])
            ->andFilterWhere(['like', 'level', $this->level])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'user_id', $this->user_id]);

        return $dataProvider;
    }
}
