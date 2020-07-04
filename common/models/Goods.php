<?php
namespace common\models;

use common\components\Func;
use Yii;
use yii\behaviors\TimestampBehavior;



    /*bcadd — 将两个高精度数字相加
    bccomp — 比较两个高精度数字，返回-1, 0, 1
    bcdiv — 将两个高精度数字相除
    bcmod — 求高精度数字余数
    bcmul — 将两个高精度数字相乘
    bcpow — 求高精度数字乘方
    bcpowmod — 求高精度数字乘方求模，数论里非常常用
    bcscale — 配置默认小数点位数，相当于就是Linux bc中的”scale=”
    bcsqrt — 求高精度数字平方根
    bcsub — 将两个高精度数字相减*/


class Goods extends \yii\db\ActiveRecord{
    const CACHE_KEY_FIND_MODEL_TEXT = 'cache_key_goods_find_model_text';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','image_id','category_id','sku_type','stock','deduct_stock_type','sales_initial','sales_actual','is_free_shipping','delivery_id',
                'view','prom_id','prom_type','is_points_gift','is_points_discount','is_vip','is_fenxiao','sort','is_delete','status' ,'created_at','updated_at'], 'integer'],
            [['price','orig_price','goods_weight','commission'], 'double'],
            [['title'], 'string', 'max' => 200],
            [['productcode'], 'string', 'max' => 100],
            [['sellpoint'], 'string', 'max' => 250],
            [['sales_initial','sales_actual','delivery_id','sort','is_delete'], 'default', 'value' =>0],
            [['status'], 'default', 'value' =>1],
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
            'id'                => '商品ID',
            'image_id'         => '商品图片',
            'title'             => '商品标题',
            'sellpoint'         => '商品卖点',
            'category_id'       => '商品分类',
            'sku_type'          => '销售规格',//(单规格 多规格)
            'price'             => '销售价',
            'orig_price'        => '商品原价',
            'stock'             => '当前库存数量',
            'goods_weight'      => '商品重量(Kg)',
            'productcode'       => '商品编码',
            'deduct_stock_type' => '库存计算方式',
            'sales_initial'     => '初始销量',
            'sales_actual'      => '实际销量',
            'is_free_shipping'  => '是否包邮',
            'delivery_id'       => '运费模板',
            'view'              => '浏览次数',
            'prom_id'           => '优惠活动id',
            'prom_type'         => '优惠活动类型',//（0默认1抢购2团购3优惠促销4预售5拼团）
            'is_points_gift'    => '是否开启积分赠送',
            'is_points_discount'=> '是否允许使用积分抵扣',
            'is_vip'            => '是否开启会员折扣',
            'is_fenxiao'        => '是否开启单独分销',
            'commission'        => '分销佣金',
            'sort'              => '商品排序',
            'is_delete'         => '是否删除',//0未删除 1已删除
            'status'            => '商品状态',
            'created_at'        => '创建时间',
            'updated_at'        => '更新时间',
        ];
    }

    /**
     * 商品视图信息缓存
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/8 22:18
     */
    public static function findViewModel($id){
        $cache = self::CACHE_KEY_FIND_MODEL_TEXT;
        $info  = Yii::$app->cache->get($cache);
        if($info === false){
            $info = self::find()
                ->where("is_delete=:is_delete and id=:id")
                ->addParams([':is_delete'=>0,':id'=>$id])
                ->select("id,title,category_id,sellpoint,sku_type,deduct_stock_type,sales_initial,sales_actual,delivery_id,is_points_gift,is_points_discount,is_vip,is_fenxiao,status")
                ->asArray()
                ->one();
            if(empty($info)){
                return null;
            }
            $image = GoodsImage::findViewModel($id);
            if(!empty($image)){
                $info['image_list'] = $image;
                $info['image_id']   = $image[0];
            }else{
                $info['image_list'] = [[]];
                $info['image_id']   = 0;
            }
            Yii::$app->cache->set($cache,$info,86400);
        }
        return $info;
    }

    /**
     * 浏览+1
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/29 15:54
     */
    public static function findPreviewModel($id){
        Goods::updateAllCounters(['view'=>1], ['id' => $id]);
    }

    /**
     * @var array
     */
    public static $prom_type = [
      1 => '限时抢购',
      2 => '团购',
      3 => '优惠促销',
      4 => '预售',
      5 => '拼团',
    ];

    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/3/6 22:01
     */
    public function getPromType(){
        return self::$prom_type;
    }

    /**
     * @return mixed|string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/3/6 22:01
     */
    public function getPromTypeText(){
        if(!empty($this->prom_type)){
            return self::$prom_type[$this->prom_type];
        }else{
            return '';
        }
    }
}
