<?php
namespace common\models;

use Yii;



class GoodsDetail extends \yii\db\ActiveRecord{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_detail}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id'], 'integer'],
            [['content'], 'safe'],
            [['goods_id'], 'default', 'value' =>0],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => 'ID',
            'goods_id'          => '商品ID',
            'content'           => '商品内容',
        ];
    }


}
