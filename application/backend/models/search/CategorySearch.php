<?php

namespace backend\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use backend\models\Category;
use common\libs\Tree;

/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class CategorySearch extends Category
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id','pid', 'sort', 'list_row', 'link_id', 'allow_publish', 'display', 'reply', 'check', 'created_at', 'updated_at', 'status', 'icon'], 'integer'],
            [['name', 'title', 'extend','name','meta_title','keywords','description','groups','template_index','template_lists', 'template_detail', 'template_edit', 'model', 'model_sub', 'type', 'reply_model'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Category::scenarios();
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
        $query = Category::find();

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
            'pid' => $this->pid,
            'display' => $this->display,
            'sort' => $this->sort,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
           // ->andFilterWhere(['like', 'url', $this->url]);
           // ->andFilterWhere(['like', 'icon_style', $this->icon_style]);
        if(!isset($params['sort']))
        $query->orderBy(['sort' => SORT_ASC]);


        $arr = $query->asArray()->all();
        $treeObj = new Tree(\yii\helpers\ArrayHelper::toArray($arr));
        $treeObj->icon = ['&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ '];
        $treeObj->nbsp = '&nbsp;&nbsp;&nbsp;';
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $treeObj->getGridTree(0,'id','pid','title'),
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        return $dataProvider;
    }
}
