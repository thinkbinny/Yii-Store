<?php

namespace backend\models\search;


use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\UploadFile as common;

/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class UploadFileSearch extends common
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','domain','file_type'],'trim'],
            [['id', 'uid','group_id','is_delete'], 'integer'],
            [['name', 'domain','file_type' ], 'safe'],
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
            'uid'       => $this->uid,
            'group_id'  => $this->group_id,
            'is_delete' => $this->is_delete,
            'file_type' => $this->file_type,


        ]);
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'domain', $this->domain]);

        if(!isset($params['sort']))
        $query->orderBy(['updated_at'=>SORT_DESC,'id' => SORT_DESC]);



        return $dataProvider;

    }
}
