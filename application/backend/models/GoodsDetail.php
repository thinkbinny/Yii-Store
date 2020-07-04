<?php
namespace backend\models;

use Yii;

use common\models\GoodsDetail as common;


class GoodsDetail extends common
{


     public static function getContentText($goods_id){
         $model = self::find()
             ->where("goods_id=:goods_id")
             ->addParams([':goods_id'=>$goods_id])
             ->one();
         return $model->content;
     }
}
