<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\WxReply as Common;

/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class WxReplySearch extends Common
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'msg_type','status'], 'integer'],
            [['name'], 'safe'],
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
        $query = Common::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        //if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
          //  return $dataProvider;
        //}

        $query->andFilterWhere([
            'id'        => $this->id,
            'type'      => 1,
            'msg_type'  => $this->msg_type,
            'status'    => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
        if(!isset($params['sort']))
        $query->orderBy(['id'=>SORT_ASC]);
        return $dataProvider;
    }
}
