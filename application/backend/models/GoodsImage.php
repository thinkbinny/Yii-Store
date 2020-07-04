<?php
namespace backend\models;

use Yii;

use common\models\GoodsImage as common;
use yii\helpers\ArrayHelper;

class GoodsImage extends common
{
    /**
     * @param $goods_id
     * @param array $data
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date DateTime
     */
       public static function setImage($goods_id,$data = array()){

           $volist = self::getImageId($goods_id);
           if(empty($volist)){
                self::addImage($goods_id,$data);
           }else{
                self::updateImage($goods_id,$data,$volist);
           }
       }

    /**
     * @param $goods_id
     * @param array $volist
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/27 20:56
     */
       private static function addImage($goods_id,$volist = array()){
           foreach ($volist as $key=>$val){
               $model  = new GoodsImage();
               $model->goods_id = $goods_id;
               $model->image_id = $val;
               $model->sort     = $key;
               if(!$model->save()){
                   throw new \Exception('保存图片出错！');
               }
           }
       }

    /**
     * @param $goods_id
     * @param array $data
     * @param array $volist
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/27 21:36
     */
       private static function updateImage($goods_id,$data = array(),$volist=array()){
           $k = 0;
           foreach ($volist as $key=> $val){
               if(isset($data[$k])){
                   $image_id = $data[$k];
                   $model    = GoodsImage::findOne($key);
                   //if(!empty($model)){
                       $model->image_id = $image_id;
                       $model->sort     = $k;
                       if(!$model->save()){
                           throw new \Exception('保存图片出错！');
                       }
                   //}
                   unset($data[$k]);
                   $k++;
               }else{
                   $list = GoodsImage::deleteAll("id=:id",[':id'=>$key]);
                   if(!$list){
                       throw new \Exception('保存图片出错！');
                   }
               }
           }
           if(!empty($data)){
              self::addImage($goods_id,$data);
           }
       }

    /**
     * @param $goods_id
     * @return array|\yii\db\ActiveRecord[]
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/27 22:45
     */
     public static function getImageId($goods_id){
         $model  = new GoodsImage();
         $data   = $model::find()
             ->where("goods_id=:goods_id")
             ->addParams([':goods_id'=>$goods_id])
             ->select("id,sort,image_id")
             ->asArray()
             ->orderBy(['sort'=>SORT_ASC,'id'=>SORT_ASC])
             ->all();
         if(empty($data)){
             return null;
         }
         $data = ArrayHelper::map($data,'id','image_id');
         return $data;
     }
}
