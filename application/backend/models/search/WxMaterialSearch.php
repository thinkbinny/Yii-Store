<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\WxMaterial;
use common\libs\Tree;

/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class WxMaterialSearch extends WxMaterial
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'],'trim'],
            [['id', 'msg_type'], 'integer'],
            [['name', 'media_id'], 'safe'],
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
        $query = WxMaterial::find();

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
            'media_id'  => $this->media_id,
            'msg_type'  => $this->msg_type,

        ]);
        $query->andFilterWhere(['like', 'name', $this->name]);

        if(!isset($params['sort']))
            $query->orderBy(['updated_at'=>SORT_DESC]);
        return $dataProvider;

    }
}
