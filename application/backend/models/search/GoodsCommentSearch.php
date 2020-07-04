<?php

namespace backend\models\search;


use Yii;
use yii\data\ActiveDataProvider;
use backend\models\GoodsComment as common;


/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class GoodsCommentSearch extends common
{

    public $rangedate;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['id','status','created_at','updated_at'], 'integer'],
            [['rangedate','goods_title'], 'safe'],
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
        $query->alias('a');
        if(!empty($this->rangedate)){
            $attr = explode(' - ',$this->rangedate);
            $starttime = strtotime($attr[0]);
            $endtime   = strtotime($attr[1]);
            $query -> andWhere(['>','a.created_at',$starttime]);
            $query -> andWhere(['<','a.created_at',$endtime]);
        }




        // grid filtering conditions
        $query->andFilterWhere([
            'id'                => $this->id,
            'status'            => $this->status,
        ]);

        $query->andFilterWhere(['like', 'goods_title', $this->goods_title]);

        if(!isset($params['sort']))
        $query->orderBy(['created_at'=>SORT_DESC]);
        return $dataProvider;
    }
}
