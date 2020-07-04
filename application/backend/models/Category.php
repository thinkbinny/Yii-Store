<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property string $id
 * @property string $name
 * @property string $title
 * @property string $pid
 * @property string $sort
 * @property integer $list_row
 * @property string $meta_title
 * @property string $keywords
 * @property string $description
 * @property string $template_index
 * @property string $template_lists
 * @property string $template_detail
 * @property string $template_edit
 * @property string $link_id
 * @property integer $allow_publish
 * @property integer $display
 * @property integer $reply
 * @property integer $check
 * @property string $reply_model
 * @property string $extend
 * @property string $created_at
 * @property string $updated_at
 * @property integer $status
 * @property string $icon
 * @property string $groups
 */
class Category extends \yii\db\ActiveRecord
{
    const STATUS_DELETE = 0;
    const STATUS_ACTIVE = 1;
    public static $statusTexts = [
        self::STATUS_DELETE => '禁用',
        self::STATUS_ACTIVE => '启用',
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['created_at', 'default', 'value' => time()],
            ['updated_at', 'default', 'value' => time()],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETE]],
            ['reply_model','GetAttribute'],
            //['reply_type','GetAttribute'],
            ['extend','GetAttribute'],

            [['name', 'title','allow_publish','check','sort'], 'required'],
            ['model_id','required','message'=>'请选择内容模型'],
            [['pid', 'sort', 'list_row', 'link_id', 'allow_publish', 'display', 'reply', 'check', 'created_at', 'updated_at', 'status', 'icon'], 'integer'],
            [['extend'], 'string'],
            [['name'], 'string', 'max' => 30],
            [['title', 'meta_title'], 'string', 'max' => 50],
            [['keywords', 'description', 'groups'], 'string', 'max' => 255],
            [['template_index', 'template_lists', 'template_detail', 'template_edit', 'reply_model'], 'string', 'max' => 100],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'name'          => '栏目标识',
            'title'         => '栏目名称',
            'subtitle'      => '副名称',
            'pid'           => '栏目标识',
            'model_id'      => '内容模型',
            'sort'          => '排序',
            'list_row'      => '列表行数',
            'meta_title'    => '网页标题',
            'keywords'      => '关键字',
            'description'   => '描述',
            'template_index' => '频道模板',
            'template_lists' => '列表模板',
            'template_detail' => '详情模板',
            'template_edit' => '编辑模板',
            'link_id'       => 'Link ID',
            'allow_publish' => '发布内容',
            'display'       => '可见性',
            'reply'         => 'Reply',
            'check'         => '是否审核',
            'reply_model'   => 'Reply Model',
            'extend'        => 'Extend',
            'created_at'    => '创建时间',
            'updated_at'    => '更新时间',
            'status'        => 'Status',
            'icon'          => '图标',
            'groups'        => '分组定义',
        ];
    }
    public function GetAttribute($label){
        if($label=='extend'){
            if(!empty($this->$label)){
                $this->$label = (string)json_decode($this->$label, true);
            }else{
                $this->$label ='';
            }
        }else{
                 /* 分割模型 */
            if(!empty($this->$label)){
                $this->$label = (string)implode(',', $this->$label);
            }
        }
    }
    /*
     * 获取模块ID
     */
    public function GetModelId(){
        $cachekey = 'GetModelId_category_show';
        $data = Yii::$app->cache->get($cachekey);
        if(empty($data)){
            $volist = Model::find()->where('category_show=:category_show')->addParams([':category_show'=>1])->select('id,title')->asArray()->all();
            $data   = array();
            foreach ($volist as $vo){
                $data[$vo['id']] = $vo['title'];
            }
            Yii::$app->cache->set($cachekey,$data,8600);
        }
        return $data;
    }
    /*
     * 获取栏目列表
     *
     *
     */
    public function GetCategoryList(){
        $volist = $this->find()->where('status=:status')->addParams([':status'=>1])->select('id,model_id,pid,title')->asArray()->orderBy('id asc,sort asc')->all();
        $treeObj = new \common\libs\Tree(\yii\helpers\ArrayHelper::toArray($volist));
        $treeObj->icon = ['&nbsp;&nbsp;│', '&nbsp;&nbsp;├ ', '&nbsp;&nbsp;└ '];
        $treeObj->nbsp = '&nbsp;';
        $volist = $treeObj->getGridTree(0,'id','pid','title');
        return $volist;
    }
    /**
     * @return array
     */
    public function GetCategoryInfo($id){
        $info =  $this->find()
            ->where('id=:id')
            ->addParams([':id'=>$id])
            ->select('id,model_id,name,title')
            ->asArray()
            ->one();
        return $info;
    }
    /*
     *
     */
    public function getAllowpublish(){
       return [
            0=>'不允许',
            1=>'仅允许后台',
            2=>'允许前后台'
        ];
    }


    /*
     *
     */
    public function getCheck(){
        return [
            0=>'不需要',
            1=>'需要'
        ];
    }
    /*
     *
     */
    public function getDisplay(){
        return [
            0 => '所有人可见',
            1 => '不可见',
            2 => '管理员可见',
        ];
    }
    /**
     * 获取模型名称
     */
    public function getModelNameText($model_id){
        $Key = 'getmodelname'.$model_id;
        $title = Yii::$app->cache->get($Key);
        if(empty($title)){
        $info = Model::find()
            ->where('id=:id')
            ->addParams([':id'=>$model_id])
            ->select('title')
            ->asArray()
            ->one();
         $title =   $info['title'];
         Yii::$app->cache->set($Key,$title,8600);
        }
        return $title;
    }
}
