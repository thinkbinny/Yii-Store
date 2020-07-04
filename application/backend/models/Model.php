<?php

namespace backend\models;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "{{%model}}".
 */
class Model extends \yii\db\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%model}}';
    }

    /**
     * @inheritdoc [['password'], 'required', 'on' => ['create']],
     */
    public function rules()
    {
        return [
            [['name','title','extend','engine_type','sort'], 'required'],
            [['sort','extend','status','category_show','created_at','updated_at'], 'integer'],
            [['title', 'name','engine_type'], 'string', 'max' => 30],
            [['tablename'], 'string', 'max' => 50],
            [['status','category_show'], 'default', 'value' => 1],
            ['sort', 'default', 'value' => 50],
            //['tablename', 'getTablename','on' => ['create']],
            ['name','match','pattern'=>'/^[a-z0-9\-_]+$/','message'=>'数据表名必须是英文或数字'],
            ['name','unique','message'=>'数据表名已存在，请换一个'],

        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => '编号',
            'name'          => '数据表名',
            'title'         => '模型名称',
            'engine_type'   => '引擎类型',
            'extend'        => '继承的模型',
            'created_at'    => '创建时间',
            'updated_at'    => '更新时间',
            'category_show' => '栏目是否显示',
            'status'        => '状态',
            'sort'          => '排序',
        ];
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),

        ];
    }
    public function afterValidate()
    {
        $this->tablename       = $this->getTablename();

    }

    protected function getTablename(){
        if($this->extend==0){
            return $this->name;
        }else{
            $id = $this->extend;
            $vo = $this->find()
                ->where('id=:id')
                ->addParams([':id'=>$id])
                ->select('name')
                ->asArray()
                ->one();
            return $vo['name'].'_'.$this->name;
        }
    }
    /*
     * 添加修改
     */
    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['name', 'title', 'engine_type', 'extend' ,'sort'];//tablename
        $scenarios['update'] = ['name', 'title', 'engine_type', 'extend' ,'sort'];
        $scenarios['updatesort'] = [ 'sort'];
        return $scenarios;
    }


    /**
     * 获取引擎类型
     */
    public function getEngineType() {
        return [
            'MyISAM'    =>'MyISAM',
            'InnoDB'    =>'InnoDB',
            'MEMORY'    =>'MEMORY',
            'BLACKHOLE' =>'BLACKHOLE',
            'MRG_MYISAM'=>'MRG_MYISAM',
            'ARCHIVE'   =>'ARCHIVE',
        ];
    }
    /*
     *模型类别  类型：1  2
     */
    public function getExtend(){
        $volist = $this::find()
            ->where('extend=:extend AND status=:status')
            ->addParams([':extend'=>0,':status'=>1])
            ->select('id,title')
            ->asArray()
            ->all();
        $data = [
            '0'=>'独立模型',
        ];
        foreach ($volist as $val){
            $data[$val['id']] = $val['title'];
        }
        return $data;
    }
    public function CorrelationModel($model_id){
        $info = $this::find()
            ->where('id=:id')
            ->addParams([':id'=>$model_id])
            ->select('extend')
            ->one();
        if(empty($info)){
            $data = [['model_id'=>$model_id]];

        }elseif(empty($info->extend)){
            $data = [['model_id'=>$model_id]];
        }else{
            $data = [['model_id'=>$info->extend],['model_id'=>$model_id]];
        }
        return $data;
    }



}
