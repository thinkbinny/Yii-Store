<?php

namespace backend\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use backend\models\OrderRefund as common;


/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class OrderRefundSearch extends common
{
    public $rangedate;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rangedate','order_sn'], 'trim'],
            [['id','uid','model_type','type','status','is_delete'], 'integer'],
            [['rangedate','order_sn'], 'safe'],
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

        if($this->model_type == 2){ //商品
            $query->joinWith('goods');
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'a.id'                => $this->id,
            'a.uid'               => $this->uid,
            'a.type'              => $this->type,
            'a.status'            => $this->status,
            'a.is_delete'         => 0,
            'a.model_type'        => $this->model_type,
            //'order_sn'          => $this->order_sn,
        ]);

        $query->andFilterWhere(['like', 'a.order_sn', $this->order_sn]);
        if(!isset($params['sort']))
        $query->orderBy(['a.created_at'=>SORT_DESC]);
        //echo $query->createCommand()->getRawSql();exit;
        return $dataProvider;
    }
}
