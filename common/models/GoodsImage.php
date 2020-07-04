<?php
namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;


class GoodsImage extends \yii\db\ActiveRecord{

    //const CACHE_KEY_LIST = 'cache_key_goods_category_list';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_image}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','goods_id','image_id','sort','created_at'], 'integer'],
            [['goods_id','image_id','sort'], 'default', 'value' =>0],
            [['created_at'], 'default', 'value' =>time()],
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
            'image_id'          => '图片ID',
            'sort'              => '图片顺序',
            'created_at'        => '创建时间',
        ];
    }

    /**
     * @param $goods_id
     * @return array|\yii\db\ActiveRecord[]
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/8 22:17
     */
    public static function findViewModel($goods_id){
        $data =  self::find()
            ->where("goods_id=:goods_id")
            ->addParams([':goods_id'=>$goods_id])
            ->select("image_id,sort")
            ->orderBy(['sort'=>SORT_ASC])
            ->asArray()
            ->all();
        $data = ArrayHelper::map($data,'sort','image_id');
        return $data;
    }
}
