<?php
namespace backend\models;

use Yii;
use common\models\StoreOrderCheck as common;

class StoreOrderCheck extends common
{




    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/18 16:59
     */
    public function getShopData(){
        return Store::getFindListModel();
    }

    /**
     * @return string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/18 17:00
     */
    public function getShopNameText(){
        $shop_id = $this->shop_id;
        $data = Store::getFindListModel();
        if(isset($data[$shop_id])){
            return $data[$shop_id];
        }else{
            return '';
        }
    }

}
