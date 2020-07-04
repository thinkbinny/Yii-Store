<?php
namespace backend\models;

use Yii;
use common\models\Region as common;

class Region extends common
{

    /**
     * 获取到市级数组
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/3 17:39
     */
    public static function getProvinceAndCityData(){
        $volist = self::getFindDataList(0);
        $data   = array();
        foreach ($volist as $key=> $vo){
            $vo['city'] = self::getFindDataList($vo['id']);
            $data[$key] = $vo;
        }
        return $data;
    }


    public static function getAddressText($province_id,$city_id=0,$district_id=0){
        $data = self::getRegionAll();
        $html = '';
        if(isset($data[$province_id])){
            $html .= $data[$province_id].' ';
        }
        if(isset($data[$city_id])){
            $html .= $data[$city_id].' ';
        }
        if(isset($data[$district_id])){
            $html .= $data[$district_id].' ';
        }
        return $html;
    }
}
