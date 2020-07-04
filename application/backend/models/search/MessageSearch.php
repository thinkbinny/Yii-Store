<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Message;

/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class MessageSearch extends Message
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','uid' ,'type', 'send_type', 'status'], 'integer'],
            [['title'], 'safe'],
            //['q','']
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
        $query = Message::find();

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
            'id'    => $this->id,
            'uid'   => $this->uid,
            'type'  => $this->type,
        ]);
        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['in', 'status', [0,1]]);
        if(!isset($params['sort']))
        $query->orderBy(['status'=>SORT_DESC]);
        return $dataProvider;

    }
}
