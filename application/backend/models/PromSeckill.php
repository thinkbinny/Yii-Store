<?php
namespace backend\models;

use common\components\Func;
use Yii;
use common\models\PromSeckill as common;
use yii\helpers\Json;
use yii\web\Response;


class PromSeckill extends common
{
    public $rangedate;
    public $sku;
    public function rules()
    {
        return [
            [['title','description'], 'trim'],
            [['title'], 'required'],
            [['goods_id'], 'required','message'=>'请选择{attribute}'],
            [['price','stock','buy_limit'], 'required'],
            //[['start_time','end_time'], 'required','message'=>'请选择{attribute}'],
            [['rangedate','delivery_time'], 'required','message'=>'请选择{attribute}','on'=>['create','update']],
            [['delivery_time'], 'required','message'=>'请选择{attribute}'],
            [['sort'], 'required'],
            [['goods_id','sku_id','stock','sales','buy_limit','start_time','end_time','delivery_time','sort','is_delete','status','created_at','updated_at'], 'integer'],
            ['is_delete', 'in', 'range' => [0, 1]],//是否
            ['status', 'in', 'range' => [-1,0,1,2]], ///0未开始、1进行中（即将开始）、2已过期
            [['title','description'], 'string', 'max' => 200],
            [['sku_price'], 'string', 'max' => 500],
            [['sku'], 'safe'],
            [['price'], 'double'],
            [['description'], 'default', 'value' => ''],
            [['sku_id','is_delete'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => 0],
            [['sort'], 'default', 'value' => 50],

        ];

    }
    public function scenarios()
    {
        $scenarios =  parent::scenarios(); // TODO: Change the autogenerated stub
        $scenarios['create'] = ['title','description','goods_id','price','sku','stock','buy_limit','rangedate','delivery_time','sort'];
        $scenarios['update'] = ['title','description','goods_id','price','sku','stock','buy_limit','rangedate','delivery_time','sort'];
        return $scenarios;
    }

    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/3/4 13:39
     */
    public function attributeLabels(){
        $attributeLabels = parent::attributeLabels();
        $attributeLabels['rangedate'] = '抢购时间';
        $attributeLabels['sort']      = '抢购顺序';
        return $attributeLabels;
    }
    /**关联商品信息**/
    public function getGoods(){
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }
    /**
     * @param $image_id
     * @return mixed|string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/10 21:40
     */
    public function getImageUrl($image_id){
        return Func::getImageUrl($image_id);
    }

    /**
     * 验证之前
     * @return bool
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/3/6 18:02
     */
    public function beforeValidate()
    {
        if( $this->scenario == 'create' || $this->scenario == 'update' ){
            $sku_price = '';
            $stock     = 0;
            if($this->sku_id == 0){
                $price = 0;
                if(!empty($this->sku)){
                    $sku_price = array();
                    foreach ($this->sku as $key=> $vo){
                        if($price>$vo['discount_price'] || $price==0){
                            $price = $vo['discount_price'];
                        }
                        $sku_price[$key]['discount_price'] = bcadd($vo['discount_price'],0,2);
                        $stock = $stock + $vo['stock'];
                    }
                    $sku_price = Json::encode($sku_price);
                    $this->price = $price;
                }
            }else{
                $goods = Goods::find()
                    ->where("id=:id")
                    ->addParams([':id'=>$this->goods_id])
                    ->select('stock')
                    ->one();
                $stock = $goods->stock;
            }
            if(!empty($this->stock)){
                if($stock < $this->stock){
                    $this->addError('stock','抢购库存不能大于'.$stock.'件');
                    return false;
                }
            }

            $this->sku_price = $sku_price;
            if(!empty($this->rangedate)){
                $attr = explode(' - ',$this->rangedate);
                $this->start_time = strtotime($attr[0]);
                $this->end_time   = strtotime($attr[1]);

            }
            if(!empty($this->delivery_time)){
                $this->delivery_time = strtotime($this->delivery_time);
            }
            //转换时间
        }
        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }

    /**
     * 验证之后
     * @return bool
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/3/6 17:38
     */
    public function afterValidate()
    {


        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }

    /**
     * 保存数据
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/3/6 12:50
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        $transaction = Yii::$app->db->beginTransaction();  // 创建事务
        try {
            //修改 扣库 模式
            if(parent::save($runValidation, $attributeNames)) {
                if( $this->isNewRecord ){
                    $prom_id = Yii::$app->db->getLastInsertID();
                }else{
                    $prom_id = $this->id;
                }
                $data = [
                    'deduct_stock_type' => 1,//下单减库存
                    'prom_id' => $prom_id, //指定抢购模式ID
                    'prom_type' => 1, //抢购模式
                ];
                Goods::updateAll($data, 'id=:id', [':id' => $this->goods_id]);
                $transaction->commit();  // 提交
                return true;
            }else{
                $transaction->rollBack();  // 回滚
                return false;
            }
        }catch(\Exception $exception){
            header('content-type:application/'.Response::FORMAT_JSON.';charset=utf-8');
            $message = $exception->getMessage();
            $transaction->rollBack();  // 回滚
            $data = ['status'=>false,'message'=>$message];
            exit(json_encode($data));
        }

        //return parent::save($runValidation, $attributeNames); // TODO: Change the autogenerated stub
    }

}
