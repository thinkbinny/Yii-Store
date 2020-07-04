<?php
namespace backend\models;

use Yii;

use common\models\GoodsAttrRelation as common;
use yii\helpers\ArrayHelper;

class GoodsAttrRelation extends common
{

    public function rules()
    {
        return [
            [['attr_name','params'], 'trim'],
            [['goods_id','attr_id','attr_name'], 'required'],
            [['goods_id','attr_id','created_at','updated_at'], 'integer'],
            [['attr_name'], 'string', 'max' => 50],
            ['params', 'safe'],
        ];
    }

    /**
     * @param $goods_id
     * @return array|null
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/31 21:50
     */
    public static function getSkuAttrList($goods_id){
        $volist = self::find()
            ->where("goods_id=:goods_id")
            ->addParams([':goods_id'=>$goods_id])
            ->select('id,attr_id,attr_name,params')
            ->asArray()
            ->orderBy(['updated_at'=>SORT_ASC,'id'=>SORT_ASC])
            ->all();
        if(empty($volist)){
            return null;
        }else{
            $data = array();
            foreach ($volist as $vo){
                $data[] = [
                    //'id'        =>  $vo['id'],
                    'attr_id'   =>  $vo['attr_id'],
                    'attr_name' =>  $vo['attr_name'],
                    'attr_items'=>  json_decode($vo['params'],true),
                ];
            }
            return $data;
        }
    }



    /**
     * @param $goods_id
     * @param array $newData
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/31 18:07
     */
    public static function setAttrRelation($goods_id,$newData = array()){
        $oldData = self::getOldAttrRelationList($goods_id);
        if(empty($oldData)){
            self::addAttrRelation($goods_id,$newData);
        }else{
            self::updateAttrRelation($goods_id,$newData,$oldData);
        }
    }
    /**
     * @param $goods_id
     * @param $newData
     * @throws \Exception
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/31 20:02
     */
    public static function addAttrRelation($goods_id,$newData){
        $model = new self();
        foreach ($newData as $attributes){
            $_model = clone $model;
            $_model->goods_id     = $goods_id;
            $_model->attr_id      = $attributes['attr_id'];
            $_model->attr_name    = $attributes['attr_name'];
            $_model->params       = json_encode($attributes['attr_items']);
            if(!$_model->save()){
                throw new \Exception('保存规格属性出错！');
            }
        }
    }
    /**
     * @param $goods_id
     * @param $data
     * @param $oldData
     * @throws \Exception
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/31 20:02
     */
    public static function updateAttrRelation($goods_id,$newData,$oldData){
        $i=0;
        foreach ($oldData as $sku_id=> $sku_attr_id){
            if(isset($newData[$i])){
                $model    = self::findOne($sku_id);
                $model->attr_id       = $newData[$i]['attr_id'];
                $model->attr_name     = $newData[$i]['attr_name'];
                $model->params        = json_encode($newData[$i]['attr_items']);
                if(!$model->save()){
                    throw new \Exception('保存规格属性出错！');
                }
                unset($newData[$i]);
            }else{
                $list = self::deleteAll("id=:id",[':id'=>$sku_id]);
                if(!$list){
                    throw new \Exception('保存规格属性出错！');
                }
            }
            $i++;
        }
        if(!empty($newData)){
            self::addAttrRelation($goods_id,$newData);
        }
    }
    /**
     * @param $goods_id
     * @return array|null|\yii\db\ActiveRecord[]
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/31 17:59
     */
    public static function getOldAttrRelationList($goods_id){
        $model  = new self();
        $data   = $model::find()
            ->where("goods_id=:goods_id")
            ->addParams([':goods_id'=>$goods_id])
            ->select("id,attr_id")
            ->asArray()
            ->orderBy(['updated_at'=>SORT_ASC,'id'=>SORT_ASC])
            ->all();
        if(empty($data)){
            return null;
        }
        $data = ArrayHelper::map($data,'id','attr_id');
        return $data;
    }
}
