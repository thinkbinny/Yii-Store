<?php

namespace backend\models\search;


use Yii;
use yii\data\ActiveDataProvider;
use backend\models\Integral as common;


/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class IntegralSearch extends common
{

    public $rangedate;
    public $q;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['id','uid','type','status','created_at','updated_at'], 'integer'],
            [['q'], 'safe'],
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
            'id'                => $this->id,
            'uid'               => $this->uid,
            'type'              => $this->type,
            'status'            => $this->status,
        ]);

        //$query->andFilterWhere(['like', 'name', $this->name]);

        if(!isset($params['sort']))
        $query->orderBy(['id'=>SORT_DESC]);
        return $dataProvider;
    }
}
