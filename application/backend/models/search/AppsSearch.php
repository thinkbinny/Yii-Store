<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Apps;

/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class AppsSearch extends Apps
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id','app_name'],'trim'],
            [['id', 'uid', 'status'], 'integer'],
            [['app_id', 'app_name', ], 'safe'],
        ];
    }
    public function afterValidate(){
        return true;
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
        $query = Apps::find();

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
            'app_id'    => $this->app_id,
        ]);
        /*$query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'url', $this->url]);*/
        if(!isset($params['sort']))
        $query->orderBy(['status'=>SORT_DESC,'id' => SORT_ASC]);
        return $dataProvider;

    }
}
