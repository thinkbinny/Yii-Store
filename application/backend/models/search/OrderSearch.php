<?php

namespace backend\models\search;

use backend\models\Member;
use Yii;
use yii\data\ActiveDataProvider;
use backend\models\Order as common;


/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class OrderSearch extends common
{
    public $rangedate;
    public $q;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['q','rangedate','order_sn'], 'trim'],
            [['id','uid','delivery_type','extract_shop_id','pay_status','delivery_status','receipt_status','order_status'], 'integer'],
            [['q','rangedate','order_sn'], 'safe'],
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

        if(!empty($this->q)){
            $uid = Member::getSearchUid($this->q);
            if(empty($uid)){
                $this->order_sn = $this->q;
            }else{
                $this->uid = $uid;
            }
        }


        // grid filtering conditions
        $query->andFilterWhere([
            'id'                => $this->id,
            'uid'               => $this->uid,
            'pay_status'        => $this->pay_status,
            'delivery_status'   => $this->delivery_status,
            'receipt_status'    => $this->receipt_status,
            'order_status'      => $this->order_status,
            'extract_shop_id'   => $this->extract_shop_id,
            'delivery_type'     => $this->delivery_type,
            //'order_sn'          => $this->order_sn,
        ]);

        $query->andFilterWhere(['like', 'order_sn', $this->order_sn]);

        $query->joinWith('goods');


        if(!isset($params['sort']))
        $query->orderBy(['created_at'=>SORT_DESC]);
        return $dataProvider;
    }
}
