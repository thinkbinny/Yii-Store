<?php

namespace backend\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use backend\models\GoodsCategory as common;
use common\libs\Tree;

/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class GoodsCategorySearch extends common
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','is_delete','status'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return common::scenarios();
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
            'status'    => $this->status,
            'is_delete' => 0,

        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
           // ->andFilterWhere(['like', 'url', $this->url]);
           // ->andFilterWhere(['like', 'icon_style', $this->icon_style]);
        if(!isset($params['sort']))
        $query->orderBy(['sort' => SORT_ASC,'id'=>SORT_ASC]);


        $arr = $query->asArray()->all();
        $treeObj = new Tree(\yii\helpers\ArrayHelper::toArray($arr));
        $treeObj->icon = ['&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ '];
        $treeObj->nbsp = '&nbsp;&nbsp;&nbsp;';
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $treeObj->getGridTree(0,'id','parent_id','name'),
            'pagination' => [
                'pageSize' => 1000,
            ],
        ]);

        return $dataProvider;
    }
}
