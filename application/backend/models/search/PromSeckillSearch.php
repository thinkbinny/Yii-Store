<?php

namespace backend\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use backend\models\PromSeckill as common;


/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class PromSeckillSearch extends common
{
    public $rangedate;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'trim'],
            [['id','goods_id','start_time','end_time','status','is_delete'], 'integer'],
            [['rangedate','title'], 'safe'],
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


            $query->joinWith('goods');


        // grid filtering conditions
        $query->andFilterWhere([
            'a.id'                => $this->id,
            //'a.start_time'        => $this->start_time,
            //a.end_time'          => $this->end_time,
            'a.status'            => $this->status,
            'a.is_delete'         => 0,
            //'order_sn'          => $this->order_sn,
        ]);

        $query->andFilterWhere(['like', 'a.title', $this->title]);
        if(!isset($params['sort']))
        $query->orderBy(['a.created_at'=>SORT_DESC]);
        //echo $query->createCommand()->getRawSql();exit;
        return $dataProvider;
    }
}
