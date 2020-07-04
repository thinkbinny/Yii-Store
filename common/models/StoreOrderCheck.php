<?php
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;


class StoreOrderCheck extends \yii\db\ActiveRecord{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_order_check}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','uid','shop_id' ,'created_at','updated_at'], 'integer'],
            [['realname','order_sn'], 'string', 'max' => 20],
            [['shop_id'], 'default', 'value' =>0],

        ];
    }
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => '核销ID',
            'uid'               => '核销用户',
            'shop_id'           => '核销门店',
            'realname'          => '核销员',
            'order_sn'          => '订单编号',
            'created_at'        => '创建时间',
            'updated_at'        => '更新时间',
        ];
    }


}
