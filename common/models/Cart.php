<?php
namespace common\models;
use yii\behaviors\TimestampBehavior;
use Yii;



class Cart extends \yii\db\ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cart}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','uid','goods_id','sku_id','quantity','fengxiao_uid','created_at','updated_at'], 'integer'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => '购物车ID',
            'uid'               => '用户UID',
            'goods_id'          => '商品ID',
            'sku_id'            => '商品规则ID',
            'quantity'          => '购物数量',
            'fengxiao_uid'      => '分销用户UID',
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
