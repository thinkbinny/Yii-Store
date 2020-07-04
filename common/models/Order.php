<?php
namespace common\models;
use common\components\Func;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\behaviors\TimestampBehavior;
use Yii;



class Order extends \yii\db\ActiveRecord{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => '订单ID',
            'order_sn'          => '订单编号',
            'uid'               => '用户UID',
            'delivery_type'     => '配送方式',
            'extract_shop_id'   => '自提门店ID',
            'coupon_id'         => '优惠ID',
            'coupon_money'      => '优惠价钱',
            'redbags_id'        => '红包ID',
            'redbags_money'     => '红包价钱',
            'integral'          => '积分数量',
            'integral_money'    => '积分抵消',
            'total_price'       => '总价钱',
            'update_price'      => '修改价钱',
            'pay_price'         => '支付价钱',
            'pay_type'          => '支付类型',
            'pay_status'        => '支付状态',
            'pay_time'          => '支付时间',
            'shipping_price'    => '物流运费',
            'shipping_code'     => '物流编码',
            'shipping_company'  => '物流公司',
            'shipping_sn'       => '货运编号',
            'delivery_status'   => '发货状态',
            'delivery_time'     => '发货时间',
            'receipt_status'    => '收货状态',
            'receipt_time'      => '收货时间',
            'order_status'      => '订单状态',
            'remark'            => '备注',
            'created_at'        => '创建时间',
            'updated_at'        => '更新时间',
        ];
    }

    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/30 14:18
     */
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }



    /**
     * 生成订单号
     * @return string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/28 20:21
     */
    protected static function getOrderSn(){
        $order_id_main  = date('Ymd') .str_pad(substr(microtime(),'2',6),6,0,STR_PAD_LEFT);
        $order_id_sum   = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        $order_id       = $order_id_main . $order_id_sum; //长度20位
        return $order_id;
    }

    /**
     * 配送方式(1:微信支付,2:支付宝)
     * @var array
     */
    public static $pay_type = [
        1=>'微信支付',
        2=>'支付宝',
    ];




}
