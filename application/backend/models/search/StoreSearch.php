<?php

namespace backend\models\search;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Store as common;

/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class StoreSearch extends common
{

    public $q;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['q'],'trim'],
            //[['searchtype'],'required'],
            [['uid','status','is_delete'], 'integer'],
            [['q','name','linkman'], 'safe'],
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
            'is_delete' => 0,
        ]);
        $query->andFilterWhere(['like', 'name', $this->q])
            ->orFilterWhere(['like', 'linkman', $this->q]);
        if(!isset($params['sort']))
        $query->orderBy(['sort'=>SORT_ASC,'id' => SORT_DESC]);
        return $dataProvider;

    }
}
