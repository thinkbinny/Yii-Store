<?php

namespace backend\models\search;

use common\libs\Tree;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AppsMenu;

/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class AppsMenuSearch extends AppsMenu
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name','trim'],
            [['id', 'pid','sort', 'status'], 'integer'],
            [['name', 'desc', ], 'safe'],
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
        $query = AppsMenu::find();

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
            'status'    => $this->status,
        ]);
        /*$query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'url', $this->url]);*/
        if(!isset($params['sort']))
        $query->orderBy(['status'=>SORT_DESC,'sort'=>SORT_ASC,'id' => SORT_ASC]);

        $arr = $query->asArray()->all();
        $treeObj = new Tree(\yii\helpers\ArrayHelper::toArray($arr));
        $treeObj->icon = ['&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ '];
        $treeObj->nbsp = '&nbsp;&nbsp;&nbsp;';
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $treeObj->getGridTree(),
            'pagination' => [
                'pageSize' => 500,
            ],
        ]);

        return $dataProvider;

    }
}
