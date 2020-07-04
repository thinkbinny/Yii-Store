<?php

namespace backend\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use backend\models\Printer as common;


/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class PrinterSearch extends common
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','status'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return common::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = common::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id'            => $this->id,
            'status'        => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        if(!isset($params['sort']))
        $query->orderBy(['sort' => SORT_ASC,'updated_at'=>SORT_DESC]);
        return $dataProvider;
    }
}
