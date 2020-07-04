<?php

namespace backend\models;

use common\components\Func;
use Yii;

class Article
{
    /**
     * @inheritdoc
     */
    //protected $tablename            = '';
    protected $model_id             = 0;


    public function __construct($model_id=1)
    {
        $this->model_id     = $model_id;

    }

    /**
     * @return mixed
     */
    public function getTableName(){
        $info = Model::find()
            ->where('id=:id')
            ->addParams([':id'=>$this->model_id])
            ->select('tablename')
            ->one();
        $volist = explode('_',$info->tablename);
        $string = '';
        foreach ($volist as $val){
            $string .= ucfirst($val);
        }
        return $string;
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function ListsField(){
        $volist = ModelField::find()
            ->where('model_id=:model_id AND status=:status')
            ->addParams([':model_id'=>$this->model_id,':status'=>1])
            ->select('field,name,setting,formtype,tips,status')
            ->orderBy('status desc,sort asc,id asc')
            ->asArray()
            ->all();
        return $volist;
    }



}
