<?php

namespace backend\models\search;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Picture;

/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class PictureSearch extends Picture
{


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'],'trim'],
            [['name'], 'safe'],
            [['id', 'folder_id', 'status'], 'integer'],
          /*  ['searchtype','required'],

            */
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
        $query = Picture::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params,'');

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

        if(empty($this->folder_id)) $this->folder_id = 0;

        // grid filtering conditions
        $query->andFilterWhere([
            'id'        => $this->id,
            'status'    => $this->status,
            'uid'       => $this->uid,
            'folder_id' => $this->folder_id,
        ]);
        $query->andFilterWhere(['like', 'name', $this->name]);
        if(!isset($params['sort']))
            $query->orderBy(['filetype'=>SORT_ASC,'created_at'=>SORT_DESC]);
        return $dataProvider;

    }
}
