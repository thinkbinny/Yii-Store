<?php
namespace mobile\models;

use common\models\OrderRefund as common;
use Yii;


class OrderRefund extends common {

    public function rules()
    {
        return [
            [['refund_explain','image','remark'], 'trim'],
            [['order_id','order_sn','order_goods_id','uid','type','pay_money','refund_money','refund_deposit','refund_integral','refund_reason'], 'required'],
            [['order_id','order_goods_id','uid','model_type','type','refund_integral','is_delete','status','created_at','updated_at'], 'integer'],
            ['model_type', 'in', 'range' => [1, 2]],//1 已支付 未发货
            ['type', 'in', 'range' => [1, 2, 3]],
            ['is_delete', 'in', 'range' => [0,1]],
            ['status', 'in', 'range' => [-2,-1,0,1,2,3]],
            [['order_sn'], 'string', 'max' => 20],
            [['refund_reason'], 'string', 'max' => 50],
            [['refund_explain','image','remark'], 'string', 'max' => 250],
            [['pay_money','refund_money','refund_deposit'], 'double'],
            [['refund_explain','image','remark'], 'default', 'value' => ''],
            [['is_delete','status'], 'default', 'value' => 0],
        ];
    }



}
