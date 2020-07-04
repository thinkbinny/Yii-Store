<?php
namespace common\models;
use yii\behaviors\TimestampBehavior;
use Yii;



class OrderAddress extends \yii\db\ActiveRecord{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_address}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','order_id','province_id','city_id','district_id','created_at','updated_at'], 'integer'],
            [['name','phone'], 'string', 'max' => 50],
            [['detail'], 'string', 'max' => 250],
            [['order_id','province_id','city_id','district_id'], 'default', 'value' =>50],

        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => '收货地址ID',
            'order_id'          => '订单ID',
            'name'              => '收货人姓名',
            'phone'             => '收货人号码',
            'province_id'       => '省份',
            'city_id'           => '市区',
            'district_id'       => '县/区',
            'detail'            => '详细地址',
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

}
