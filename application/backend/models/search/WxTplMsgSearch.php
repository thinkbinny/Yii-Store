<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\WxTplMsg;


/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class WxTplMsgSearch extends WxTplMsg
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','template_sn'],'trim'],
            [['id', 'status'], 'integer'],
            [['title', 'template_sn'], 'safe'],
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
        $query = WxTplMsg::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize'=>25]
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
            'status'  => $this->status,
           // 'template_sn'  => $this->template_sn,

        ]);
        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->orFilterWhere(['like', 'template_sn', $this->title]);

        if(!isset($params['sort']))
            $query->orderBy(['id'=>SORT_DESC]);
        return $dataProvider;

    }
}
