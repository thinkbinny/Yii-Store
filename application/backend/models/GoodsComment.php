<?php
namespace backend\models;

use Yii;

use common\models\GoodsComment as common;



class GoodsComment extends common
{

    public function rules()
    {
        return [

        ];
    }


   public static $status = [
      0 =>'审核中',
      1 =>'正常显示',
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
