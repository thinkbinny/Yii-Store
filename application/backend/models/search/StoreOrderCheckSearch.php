<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\StoreOrderCheck as common;

/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class StoreOrderCheckSearch extends common
{


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['realname'],'trim'],
            [['uid','shop_id'], 'integer'],
            [['realname'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id'        => $this->id,
            'uid'       => $this->uid,
            'shop_id'   => $this->shop_id,

        ]);
        $query->andFilterWhere(['like', 'realname', $this->realname]);

        if(!isset($params['sort']))
        $query->orderBy(['created_at'=>SORT_DESC,'id' => SORT_DESC]);
        return $dataProvider;

    }
}
