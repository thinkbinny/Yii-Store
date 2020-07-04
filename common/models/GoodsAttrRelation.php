<?php
namespace common\models;
use yii\behaviors\TimestampBehavior;
use Yii;



class GoodsAttrRelation extends \yii\db\ActiveRecord{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_attr_relation}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id','attr_id','created_at','updated_at'], 'integer'],
            [['attr_name'], 'string', 'max' => 50],
            ['params', 'safe'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => '属性值ID',
            'goods_id'          => '商品ID',
            'attr_id'           => '属性ID',
            'attr_name'         => '属性名称',
            'params'            => '属性值字段串',
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
