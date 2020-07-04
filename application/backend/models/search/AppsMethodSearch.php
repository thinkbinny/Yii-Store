<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AppsMethod;

/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class AppsMethodSearch extends AppsMethod
{
    /**
     * @inheritdoc
     */
    public function rules()
    {

/*        [['method'], 'required'],
        [['apps_menu_id','auth','type','sort','status','created_at','updated_at'], 'integer'],
        [['method'], 'string', 'length' => 50],
        [['description'], 'string', 'length' => 200],
        [['request','result'], 'safe'],*/

        return [
            ['method','trim'],
            [['id','apps_menu_id', 'type','sort', 'status'], 'integer'],
            [['method'], 'safe'],
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
        $query = AppsMethod::find();

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
            'method'    => $this->method,
            'status'    => $this->status,
        ]);
        /*$query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'url', $this->url]);*/
        if(!isset($params['sort']))
        $query->orderBy(['status'=>SORT_DESC,'sort'=>SORT_ASC,'id' => SORT_ASC]);
        return $dataProvider;

    }
}
