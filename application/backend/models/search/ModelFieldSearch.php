<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use backend\models\ModelField;
use yii\data\ActiveDataProvider;

/**
 * AdminSearch represents the model behind the search form about `backend\models\Admin`.
 */
class ModelFieldSearch extends ModelField
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id'], 'integer'],
            [['field', 'name','css','formtype','pattern','errortips','setting'], 'safe'],
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
        $query = ModelField::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params,'');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        //$model_id = Yii::$app->session->get('model_id');
        $query->andFilterWhere([
            'model_id' => $this->model_id,
        ]);
        if(!isset($params['sort']))
            $query->orderBy(['sort'=>SORT_ASC,'id'=>SORT_ASC]);
        return $dataProvider;
    }
}
