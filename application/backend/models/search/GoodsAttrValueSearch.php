<?php

namespace backend\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use backend\models\GoodsAttrValue as common;


/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class GoodsAttrValueSearch extends common
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','attr_id','status'], 'integer'],
            [['value'], 'safe'],
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
            'attr_id'       => $this->attr_id,
            'status'        => $this->status,
        ]);

        $query->andFilterWhere(['like', 'value', $this->value]);

        if(!isset($params['sort']))
        $query->orderBy(['sort' => SORT_ASC,'id'=>SORT_ASC]);
        return $dataProvider;
    }
}
