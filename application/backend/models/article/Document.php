<?php
/**
 *
 */
namespace backend\models\article;
use backend\models\Category;
use Yii;


class Document extends \yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName() {
        return '{{%document}}';
    }

    /**
     * @return array
     */
    public function rules() {
        return [
            [['title'],'required'],
            [['uid', 'category_id', 'type','model_id', 'islink', 'level', 'display','view','status','created_at','updated_at'], 'integer'],
            [['name','url'], 'string', 'max' => 100],
            ['title', 'string', 'length' =>  [1, 120]],
            [['keywords','copyfrom'], 'string', 'max' => 200],
            [['description','image_id'], 'string', 'max' => 255],
            [['uid','category_id','position','islink','level','view','deadline','created_at','updated_at'], 'default', 'value' => 0],
            [['display','status'], 'default', 'value' => 1],
            [['type'], 'default', 'value' => 2],
            [['name','url'], 'default', 'value' => ''],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels() {
        return [
            'id'            => 'id',
            'uid'           => '用户ID',
            'name'          => '标识',
            'category_id'   => '所属分类',
            'image_id'       => '缩略图',
            'title'         => '文章标题',
            'keywords'      => '关键词',
            'description'   => '描述',
            'copyfrom'      => '来源',
            'model_id'      => '内容模型ID',
            'type'          => '内容类型',
            'position'      => '推荐位',
            'islink'        => '转向链接',
            'url'           => 'URL',
            'level'         => '优先级',
            'display'       => '可见性',
            'deadline'      => '截至时间',
            'view'          => '浏览量',
            'status'        => '数据状态',
            'created_at'    => '创建时间',
            'updated_at'    => '更新时间',

        ];
    }
    public function beforeValidate(){
        if(parent::beforeValidate()){
            $this->updated_at   = time();
            $this->deadline     = $this->getDeadline($this->deadline);
            $this->position     = $this->getPosition($this->position);
            if($this->isNewRecord){
                $this->created_at   = time();
                $this->model_id     = $this->getModelId($this->category_id);
            }
            return true;
        }else{
            return false;
        }
    }
    /**
     *
     */
    public function afterValidate()
    {
        //$this->model_id = $this-
        /*$this->updated_at   = time();
        $this->deadline     = $this->getDeadline($this->deadline);
        $this->position     = $this->getPosition($this->position);
        if($this->isNewRecord){
            $this->created_at   = time();
            $this->model_id     = $this->getModelId($this->category_id);
        }*/

    }
    public function getDeadline($deadline){
        if(!empty($seadline)){
            return strtotime($deadline);
        }else{
            return 0;
        }
    }
    /**
     * @param $label
     * 获取当前时间
     */
    public function getDateTime($label){
        $this->$label = time();
    }

    /*
     * 启用 禁用
     */
    public function getStatus($status='all'){
        $list = [
            -1=>'删除',
            0=>'禁用',
            1=>'正常',
            2=>'待审核',
        ];
        if($status!='all'){
            return $list[$status];
        }else{
            return $list;
        }
    }
    /**
     * @param $label
     */
    public function getModelId($category_id){

        $info = Category::find()
            ->where('id=:id')
            ->addParams([':id'=>$category_id])
            ->select('model_id')
            ->one();
        if(empty($info)){
            return 0;
        }else{
            return $info->model_id;
        }
    }

    /**
     * @param $label
     */
    public function getPosition($position){
        if(!is_array($position)){
            return 0;
        }else{
            $pos = 0;
            foreach ($position as $key=>$value){
                $pos += $value;		//将各个推荐位的值相加
            }
            return $pos;
        }
    }

    /**
     * @param $category_id
     * @return mixed
     */
    public function getCategoryName($category_id){
        $info = Category::find()
            ->where(':id=:id')
            ->addParams([':id'=>$category_id])
            ->select('title')
            ->one();
        return $info->title;//$this->category_id;
    }
}
