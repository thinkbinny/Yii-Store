<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\article\Document as Article;
/**
 * AdminSearch represents the model behind the search form about `backend\models\Admin`.
 */
class ArticleSearch extends Article
{


    public function rules()
    {
        return [
            //['category_id','required'],
            [['category_id', 'status'], 'integer'],
            [['title'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        //$model = $this->TableName;
        return Article::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params,$querystatus=[])
    {
        $query = Article::find();
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $this->load($params,'');
        /*if(isset($params['category_id'])){
            $this->category_id = $params['category_id'];
        }*/
        //print_r($params);exit;
        if (!$this->validate()) {
            return $dataProvider;
        } //

        // grid filtering conditions
        $query->andFilterWhere([
            'id'            => $this->id,
            'status'        => $this->status,
            'category_id'   => $this->category_id,
        ]);
        if(isset($params['timestart']) && isset($params['timeend'])){
            $timestart = strtotime($params['timestart']);
            $timeend   = strtotime($params['timeend']) + 86400;
            $query->andFilterWhere(['AND',"created_at>={$timestart}","created_at<={$timeend}"]);
        }

        $query->andFilterWhere($querystatus);
        $query->andFilterWhere(['like', 'title', $this->title]);
        if(!isset($params['sort']))
            $query->orderBy('created_at desc');
        return $dataProvider;
    }
}
