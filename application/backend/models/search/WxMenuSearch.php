<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\WxMenu;
use common\libs\Tree;

/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class WxMenuSearch extends WxMenu
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'pid', 'display', 'sort'], 'integer'],
            [['name', 'url', 'type'], 'safe'],
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
        $query = WxMenu::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'pid' => $this->pid,
            //'display' => $this->display,
            'sort' => $this->sort,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'type', $this->type]);
        if(!isset($params['sort']))
        $query->orderBy(['sort' => SORT_ASC,'id'=>SORT_ASC]);


        $arr = $query->asArray()->all();
        $treeObj = new Tree(\yii\helpers\ArrayHelper::toArray($arr));
        $treeObj->icon = ['&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ '];
        $treeObj->nbsp = '&nbsp;&nbsp;&nbsp;';
        //print_r($treeObj->getGridTree());exit;
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $treeObj->getGridTree(),
            'pagination' => [
                'pageSize' => 500,
            ],
        ]);

        return $dataProvider;
    }
}
