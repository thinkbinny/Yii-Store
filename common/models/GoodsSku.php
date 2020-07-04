<?php
namespace common\models;
use yii\behaviors\TimestampBehavior;
use Yii;



class GoodsSku extends \yii\db\ActiveRecord{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_sku}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id','image_id','stock','created_at','updated_at'], 'integer'],
            [['sku_attr_id','barcode','productcode'], 'string', 'max' => 100],
            [['price','orig_price','goods_weight'], 'double'],
            [['barcode','productcode'], 'default', 'value' =>''],

        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                 => 'SkuID',
            'goods_id'           => '商品ID',
            'image_id'           => '图片ID',
            'stock'              => '库存数量',
            'price'              => '商品价钱',
            'orig_price'         => '划线价',
            'sku_attr_id'        => '属性ID字符串',//多个用 “_”隔开
            'barcode'            => '条形码',
            'productcode'        => '产品编码',
            'goods_weight'       => '商品重量',
            'created_at'         => '创建时间',
            'updated_at'         => '更新时间',
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
