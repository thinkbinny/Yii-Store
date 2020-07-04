<?php

namespace backend\models\search;

use common\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AdminSearch represents the model behind the search form about `backend\models\Admin`.
 */
class UserSearch extends \backend\models\Member
{
    public $searchtype;
    public $title;

    public function __construct()
    {
        parent::__construct();
        $this->searchtype = 'nickname';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nickname','searchtype','title'], 'trim'],
            [['uid','integral','login','reg_time','last_login_time','status'], 'integer'],
            [['nickname','searchtype','title'], 'safe'],
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
        $query = \backend\models\Member::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // $query->where('0=1');
            return $dataProvider;
        }
         switch ($this->searchtype) {
             case 'uid':

                 $this->uid = $this->title;
                 break;
             case 'nickname':
                 $this->nickname = $this->title;
                 break;
             case 'username':
                 $info = User::find()->where('username=:title')->addParams([':title'=>$this->title])->select('id')->asArray()->one();
                 if(!empty($info))
                 $this->uid = $info['id'];
                 break;
             case 'email':
                 $info = User::find()->where('email=:title')->addParams([':title'=>$this->title])->select('id')->asArray()->one();
                 if(!empty($info))
                     $this->uid = $info['id'];
                 break;
             case 'mobile':
                 $info = User::find()->where('mobile=:title')->addParams([':title'=>$this->title])->select('id')->asArray()->one();
                 if(!empty($info))
                     $this->uid = $info['id'];
                 break;
             default:
                 $this->uid = $this->title;
         }

        // grid filtering conditions
        $query->andFilterWhere([
            'status' => $this->status,
            'uid'    => $this->uid,
        ]);
        $query->andFilterWhere(['like', 'nickname', $this->nickname]);
        if(!isset($params['sort']))
        $query->orderBy(['uid'=>SORT_DESC]);//SORT_ASC
        return $dataProvider;
    }
}
