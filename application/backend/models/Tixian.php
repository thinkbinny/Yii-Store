<?php
namespace backend\models;

use Yii;

use common\models\Tixian as common;



class Tixian extends common
{

   /* public function rules()
    {
        return [

        ];
    }*/


    public static $status = [
      -1=>'不通过',
      0 =>'待审核',
      1 =>'审核通过',
      2 =>'已打款',

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


    /**
     * @var array
     */
    public static $type = [
        1 => '支付宝',
        2 => '银卡账号',
    ];
    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date DateTime
     */
    public function getType(){
        return self::$type;
    }

    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/3/8 14:55
     */
    public function getTypeText(){
        return self::$type[$this->type];
    }



}
