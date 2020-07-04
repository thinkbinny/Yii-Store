<?php
namespace backend\models;

use Yii;

use common\models\OrderGoods as common;
use yii\web\Response;


class OrderGoods extends common
{

    public function rules()
    {
        return [
            [['attr_id','value'], 'trim'],
            [['value'], 'required'],
            [['attr_id'], 'required'],
            [['attr_id','sort','status','created_at','updated_at'], 'integer'],
            [['value'], 'string', 'max' => 20],
            [['status'], 'default', 'value' =>1],
            [['sort'], 'default', 'value' =>50],
            ['value','default','value'=>''],
            //['value','verifyValue']
        ];
    }


    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/17 11:58
     */
    public function getStatus(){
        return self::$status;
    }



}
