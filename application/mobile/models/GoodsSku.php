<?php
namespace mobile\models;

use common\components\Func;
use common\models\GoodsSku as common;
use Yii;


class GoodsSku extends common {
    /**
     * 此方法只是查看库存 加入缓存，只有库存被修改才删除缓存
     * @param $goods_id
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/7 0:10
     */
    public static function findViewModel($goods_id){
        $volist = self::find()
            ->where("goods_id=:goods_id and stock>:stock")
            ->addParams([':goods_id'=>$goods_id,':stock'=>0])
            ->select("id as skuId,image_id,stock,price,orig_price,sku_attr_id")
            ->indexBy('skuId')
            ->asArray()
            ->all();
        //SKU图片转换
        $data = array();
        foreach ($volist as $key=> $vo){
            $vo['pic_url'] = Func::getImageUrl($vo['image_id']);
            //赋值 sku_attr_id
            $attrData = explode('_',$vo['sku_attr_id']);
            foreach ($attrData as $attr_id){
               $attr = GoodsAttrValue::findModelData($attr_id);
               if(!empty($attr)){
                   $vo[$attr['name']] = $attr['value'];
               }
            }
            unset($vo['image_id']);
            unset($vo['sku_attr_id']);
            $data[$key] = $vo;
        }

        return $data;
    }

    /*public static function findViewModel($goods_id){
        $volist = self::find()
            ->where("goods_id=:goods_id")
            ->addParams([':goods_id'=>$goods_id])
            ->select("id as skuId,image_id,stock,price,orig_price,sku_attr_id")
            ->asArray()
            ->all();
        //SKU图片转换
        $data = array();
        foreach ($volist as $key=> $vo){
            $vo['pic_url'] = Func::getImageUrl($vo['image_id']);
            //赋值 sku_attr_id
            $attrData = explode('_',$vo['sku_attr_id']);
            foreach ($attrData as $attr_id){
                $attr = GoodsAttrValue::findModelData($attr_id);
                if(!empty($attr)){
                    $vo[$attr['name']] = $attr['value'];
                }
            }
            unset($vo['image_id']);
            unset($vo['sku_attr_id']);
            $data[$key] = $vo;
        }

        return $data;
    }*/
}
