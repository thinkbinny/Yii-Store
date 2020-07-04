<?php
namespace mobile\models;

use common\components\Func;
use common\models\GoodsImage as common;
use Yii;


class GoodsImage extends common {
    /**
     * @param $image_id
     * @param bool $isOne
     * @return array|mixed|string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/8 20:53
     */
    public function getPicUrl($image_id,$isOne=true){
        if(empty($image_id)){
            return '';
        }
        if(is_array($image_id)){ // 是数组 返回数组

            if($isOne==true){
                $image_id = current($image_id);
                return Func::getImageUrl($image_id);
            }

            $data  = array();
            foreach ($image_id as $key => $vo){
                $data[$key] = Func::getImageUrl($vo);
            }
            return $data;
        }else{
            return Func::getImageUrl($image_id);
        }

    }


}
