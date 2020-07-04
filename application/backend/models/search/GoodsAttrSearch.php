<?php

namespace backend\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use backend\models\GoodsAttr as common;


/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class GoodsAttrSearch extends common
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','type','model_type','status'], 'integer'],
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
            'model_type'    => $this->model_type,
            'type'          => $this->type,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        if(!isset($params['sort']))
        $query->orderBy(['sort' => SORT_ASC,'id'=>SORT_ASC]);
        return $dataProvider;
    }
}
