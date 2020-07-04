<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\WxUser;

/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class WxUserSearch extends WxUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nickname'],'trim'],
            [['id', 'uid', 'subscribe', 'sex'], 'integer'],
            [['nickname', 'appid', 'openid'], 'safe'],
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
        $query = WxUser::find();

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
            'id' => $this->id,
            'uid' => $this->uid,
            'subscribe' => $this->subscribe,
            'sex' => $this->sex,

        ]);
        $query->andFilterWhere(['like', 'nickname', $this->nickname]);
        if(!isset($params['sort']))
        $query->orderBy(['created_at'=>SORT_DESC]);
        return $dataProvider;

    }
}
/*(107, 0, '产品管理', 'item/index', '', 1, 0),
(108, 107, '商品管理', '', '', 1, 0),
(109, 108, '商品列表', 'item/index', 'fa fa-window-restore', 1, 0),
(110, 109, '创建', 'item/create', '', 1, 0),
(111, 109, '更新', 'item/update', '', 1, 0);*/