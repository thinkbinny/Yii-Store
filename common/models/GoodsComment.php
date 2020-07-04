<?php
namespace common\models;
use yii\behaviors\TimestampBehavior;
use Yii;



class GoodsComment extends \yii\db\ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_comment}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description','remark'],'trim'],
            [['uid'],'required'],
            [['id','goods_id','goods_image_id','from_uid','is_image','sort','is_delete','status','created_at','updated_at'], 'integer'],
            [['goods_title','goods_sku_attr','content','image_list'], 'string', 'max' => 250],
            [['is_image','status'], 'in', 'range' => [0, 1]], //（1正常显示,0审核中）
            [['sort'], 'default', 'value' => 50],
            [['goods_image_id','from_uid','is_image','sort','is_delete','status'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */

    public function attributeLabels()
    {
        return [
            'id'                => '评论ID',
            'goods_id'          => '商品ID',
            'goods_title'       => '商品标题',
            'goods_image_id'    => '商品图片',
            'goods_sku_attr'    => '购买属性',
            'from_uid'          => '评论用户id',
            'score'             => '用户评分',
            'content'           => '评价内容',
            'is_image'          => '是否图片',
            'image_list'        => '图片列表',
            'sort'              => '顺序',
            'is_delete'         => '是否删除',//(1是，0否)
            'status'            => '状态',//（1正常显示,0审核中）
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
