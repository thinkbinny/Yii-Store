<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Links;

/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class LinksSearch extends Links
{
    public $searchtype;
    public $q;
    public function __construct()
    {
        parent::__construct();
        $this->searchtype = 'title';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','url','q'],'trim'],
            ['searchtype','required'],
            [['id', 'type', 'sort', 'status'], 'integer'],
            [['title', 'url', 'remark','q','searchtype'], 'safe'],
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
        $query = Links::find();

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

        if($this->searchtype=='title'){
            $this->title = $this->q;
        }elseif($this->searchtype=='url'){
            $this->url = $this->q;
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            //'status' => $this->status,
            'type' => $this->type,
            'sort' => $this->sort,
        ]);
        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'url', $this->url]);
        if(!isset($params['sort']))
        $query->orderBy(['status'=>SORT_DESC,'sort' => SORT_ASC]);
        return $dataProvider;

    }
}
