<?php
namespace backend\models;

use Yii;

use common\models\Integral as common;



class Integral extends common
{



   public static $status = [
      0 =>'已过期',
      1 =>'有效',
      2 =>'已扣除',
      3 =>'冻结中',
      4 =>'冻结返还',
      5 =>'冻结扣减',
   ];

    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/15 22:41
     */
   public function getStatus(){
        return self::$status;
   }

    /**
     * @return mixed
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/15 22:41
     */
   public function getStatusText(){
       $data = self::$status;
       return $data[$this->status];
   }
}
