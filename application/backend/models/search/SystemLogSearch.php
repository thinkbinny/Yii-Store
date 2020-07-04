<?php

namespace backend\models\search;

use common\libs\Tree;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SystemLog as models;

/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class SystemLogSearch extends models
{
    /**
     * @inheritdoc
     */
    public function rules()
    {

        return [
            [['username','realname'],'trim'],
            [['uid'], 'integer'],
            [['username','realname','action_name' ], 'safe'],
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
        $query = models::find();

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
            'username'  => $this->username,
            'realname'  => $this->realname,
        ]);

        if(!isset($params['sort']))
        $query->orderBy(['id' => SORT_DESC]);

        return $dataProvider;

    }
}
