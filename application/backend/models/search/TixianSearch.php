<?php

namespace backend\models\search;


use backend\models\Member;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Tixian;

/**
 * AdminSearch represents the model behind the search form about `backend\models\Admin`.
 */
class TixianSearch extends Tixian
{
    public $q;
    public $searchTime;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['q','account','realname','searchTime'], 'trim'],
            [['type'], 'integer'],
            [['account','realname','q'], 'safe'],
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
        $query = Tixian::find();

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
            'account'   => $this->account,
            'realname'  => $this->realname,
            'type'   => $this->type,
        ]);

        if($this->searchTime){
            $searchTime = explode(' - ',$this->searchTime);
            if( count($searchTime) > 1){
                $startTime  = strtotime($searchTime[0]);
                $endTime    = strtotime($searchTime[1]);
                $query->andFilterWhere(['>=','created_at',$startTime]);
                $query->andFilterWhere(['<=','created_at',$endTime]);
            }
        }
        if($this->q){
            $uid = array();
            if(is_numeric($this->q)){
                $uid[] = $this->q;
            }else{
                $user = Member::find()
                    ->where("nickname=:nickname")
                    ->addParams([':nickname'=>$this->q])
                    ->select("uid")
                    ->asArray()
                    ->all();
                foreach ($user as $val){
                    $uid[] = $val['uid'];
                }

            }

            $query->andFilterWhere([
                'in','uid',$uid
            ]);
        }
        if(!isset($params['sort']))
            $query->orderBy(['id'=>SORT_DESC]);
        return $dataProvider;

    }
}
