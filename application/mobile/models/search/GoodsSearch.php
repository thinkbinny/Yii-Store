<?php

namespace mobile\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use backend\models\Goods as common;


/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class GoodsSearch extends common
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','category_id','status'], 'integer'],
            [['title'], 'safe'],
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
    //public function search($params)
    public function search()
    {
        $query = common::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        //$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id'            => $this->id,
            'category_id'   => $this->category_id,
            'status'        => $this->status,
            'is_delete'     => 0,

        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        if(!isset($params['sort']))
        $query->orderBy(['sort' => SORT_ASC,'updated_at'=>SORT_DESC]);
        return $dataProvider;
    }
}
