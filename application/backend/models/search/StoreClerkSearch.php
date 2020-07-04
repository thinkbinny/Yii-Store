<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\StoreClerk as common;

/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class StoreClerkSearch extends common
{
    public $q;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['q','realname','mobile'],'trim'],
            //[['searchtype'],'required'],
            [['uid','shop_id','status','is_delete'], 'integer'],
            [['realname','mobile'], 'safe'],
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
            'status'    => $this->status,
            'uid'       => $this->uid,
            'shop_id'   => $this->shop_id,
            //'mobile'    => $this->mobile,
            'is_delete' => 0,
        ]);
        $query->andFilterWhere(['like', 'realname', $this->q]);
        $query->orFilterWhere(['like', 'mobile', $this->q]);
        if(!isset($params['sort']))
        $query->orderBy(['created_at'=>SORT_DESC,'id' => SORT_DESC]);
        return $dataProvider;

    }
}
