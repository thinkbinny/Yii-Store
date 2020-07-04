<?php
namespace common\models;

use Yii;



class OrderForm extends Order{
    public $goods;
    public $address_id;
    public $name;
    public $phone;
    public $error;
    private $city_id     =   0;

    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/7 22:11
     */
    public function rules()
    {
        return [
            [['order_sn','shipping_code','shipping_company','shipping_sn','remark'], 'trim'],
            [['order_sn','uid','delivery_type'], 'required'],
            [['total_price','pay_price'], 'required'],
            [['shipping_price'], 'required'],
            [['id','uid','delivery_type','extract_shop_id','coupon_id','redbags_id','pay_type','pay_status','pay_time'], 'integer'],
            [['delivery_status','delivery_time','receipt_status','receipt_time','order_status'], 'integer'],
            [['created_at','updated_at'], 'integer'],
            [['order_sn','shipping_code'], 'string', 'max' => 20],
            [['shipping_company','shipping_sn'], 'string', 'max' => 50],
            [['transaction_id','remark'], 'string', 'max' => 200],
            ['delivery_type','in','range'=>[1,2]],
            [['coupon_money','redbags_money','total_price','update_price','pay_price','shipping_price'], 'double'],
            [['extract_shop_id','coupon_id','coupon_money','redbags_id','redbags_money','total_price','update_price','pay_price'], 'default', 'value' =>0],
            [['pay_type','pay_status','pay_time','shipping_price','delivery_status','delivery_time','receipt_status','receipt_time'], 'default', 'value' =>0],
            [['order_status'],'default', 'value' =>1],
            [['shipping_code','shipping_company','shipping_sn','transaction_id','remark'], 'default', 'value' =>''],

            //定义收货地址
            [['address_id'], 'integer'],
            [['name','phone'], 'string', 'max' => 20],
            //定义商品数组
            ['goods', 'verifyArray'],
        ];
    }

    /**
     * @param $attribute
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/29 16:17
     */
    public function verifyArray($attribute){
        if(!is_array($this->$attribute)){
            $this->addError($attribute,'必须是一个数组');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => '订单ID',
            'order_sn'          => '订单编号',
            'uid'               => '用户UID',
            'delivery_type'     => '配送方式',
            'extract_shop_id'   => '自提门店ID',
            'coupon_id'         => '优惠ID',
            'coupon_money'      => '优惠价钱',
            'redbags_id'        => '红包ID',
            'redbags_money'     => '红包价钱',
            'total_price'       => '总价钱',
            'update_price'      => '修改价钱',
            'pay_price'         => '支付价钱',
            'pay_type'          => '支付类型',
            'pay_status'        => '支付状态',
            'pay_time'          => '支付时间',
            'shipping_price'    => '物流运费',
            'shipping_code'     => '物流编码',
            'shipping_company'  => '物流公司',
            'shipping_sn'       => '货运编号',
            'delivery_status'   => '发货状态',
            'delivery_time'     => '发货时间',
            'receipt_status'    => '收货状态',
            'receipt_time'      => '收货时间',
            'order_status'      => '订单状态',
            'remark'         => '备注',
            'created_at'        => '创建时间',
            'updated_at'        => '更新时间',
        ];
    }



    /**
     * 扣除库存
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/29 13:08
     */
    private static function SeductStock($sku_id,$quantity){

        $model = GoodsSku::find()
            ->where("id=:id")
            ->addParams([':id'=>$sku_id])
            ->select('id,goods_id,stock')
            ->one();
        $goods = Goods::find()
            ->where("id=:id")
            ->addParams([':id'=>$model->goods_id])
            ->select('id,stock')
            ->one();
        if(empty($model) || empty($goods)){
            throw new \Exception('系统出错！');
        }else{
            $skuStock   = $model->stock - $quantity;
            $goodsStock = $goods->stock - $quantity;
            if($skuStock < 0 ){
                $skuStock = 0;
            }
            if($goodsStock < 0 ){
                $goodsStock = 0;
            }
            $model->stock = $skuStock;
            $goods->stock = $goodsStock;
            if(!$model->save()){
                throw new \Exception('系统出错！');
            }
            if(!$goods->save()){
                throw new \Exception('系统出错！');
            }
        }
    }

    /**
     * @param $goods_id
     * @param $sku_id
     * @param $quantity
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/29 14:44
     */
    private static function SeductStockFront($goods_id,$sku_id,$quantity){
        Goods::updateAllCounters(['stock'=>-$quantity,'sales_actual'=>$quantity],['id'=>$goods_id]);
        GoodsSku::updateAllCounters(['stock'=>-$quantity],['id'=>$sku_id]);
    }
    /**
     * @param $goods_id
     * @param $sku_id
     * @param $quantity
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/29 14:44
     */
    private static function RestoreStock($goods_id,$sku_id,$quantity){
        $sales_actual = -$quantity;
        Goods::updateAllCounters(['stock'=>$quantity,'sales_actual'=>$sales_actual],['id'=>$goods_id]);
        GoodsSku::updateAllCounters(['stock'=>$quantity],['id'=>$sku_id]);
    }
    /**
     * @param $data
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/28 13:49
     */
    private static function getSkuGoodsInfo($data){
        $sku      = [];
        $skuData  = [];
        foreach ($data as $v){
            $sku[] = $v['sku_id'];
            $skuData[$v['sku_id']] = $v;
        }
        $volist = GoodsSku::find()
            ->alias('sku')
            ->andWhere(['in','sku.id',$sku])
            ->leftJoin(Goods::tableName().' as g','sku.goods_id = g.id')
            ->select('sku.id as sku_id,sku.goods_id,sku.sku_attr_id,sku.price,sku.stock,sku.goods_weight,sku.productcode,g.title,g.image_id,g.deduct_stock_type,g.prom_id,g.prom_type,g.is_fenxiao,g.is_vip,g.commission,g.is_points_gift,g.is_points_discount,g.is_free_shipping,g.delivery_id')
            ->asArray()
            ->all();
        $data = array();
        foreach ($volist as $value){
            $attribute = '';
            if(!empty($value['sku_attr_id'])){
                $attr = explode('_',$value['sku_attr_id']);
                foreach ($attr as $attr_id){
                    $attr = GoodsAttrValue::findModelData($attr_id);
                    $attribute .= $attr['name'].'：'.$attr['value'].'；';
                }
            }
            $value['quantity']      = $skuData[$value['sku_id']]['quantity'];
            $unionId = 0;
            if(isset($skuData[$value['sku_id']]['unionId'])){
                $unionId = $skuData[$value['sku_id']]['unionId'];
            }
            $value['unionId']       = $unionId;;
            $value['attribute']     = $attribute;
            //判断库存是否有足够 数量
            $stock = $value['stock'] - $value['quantity'];
            if($stock<0){
                throw new \Exception('商品库存不足！');
            }
            //如果拍下扣除库存执行
            if( $value['deduct_stock_type'] == 1 ){
              self::SeductStockFront($value['goods_id'],$value['sku_id'],$value['quantity']);
            }
            $data[] = $value;
        }
        return $data;
    }



    /**
     * @param $goods
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/9 14:28
     */
    private function getOrderGoods(){
        if(empty($this->goods)){
            throw new \Exception('系统出错！');
        }
        $goods_total_price = 0;
        $volist = self::getSkuGoodsInfo($this->goods);
        $data   = array();
        $time   = time();
        foreach ($volist as $vo){
            $total_price             = bcmul($vo['price'],$vo['quantity'],2);
            $goods_total_price       = bcadd($goods_total_price,$total_price,2);
            $data[] = [
                'goods_id'           => $vo['goods_id'],
                'title'              => $vo['title'],
                'image_id'           => $vo['image_id'],
                'deduct_stock_type'  => $vo['deduct_stock_type'],
                'sku_id'             => $vo['sku_id'],
                'sku_attr_id'        => $vo['sku_attr_id'],//属性ID字符串
                'sku_attr_name'      => $vo['attribute'],//属性值 数组
                'goods_weight'       => $vo['goods_weight'],//商品重量
                'productcode'        => $vo['productcode'],//商品编码
                'price'              => $vo['price'],//商品价钱
                'quantity'           => $vo['quantity'],//购买数量
                'total_price'        => $total_price,//合计价钱
                'status'             => 1,
                'created_at'         => $time,
                'updated_at'         => $time,
            ];

        }
        $this->total_price      = $goods_total_price;
        $this->pay_price        = $this->total_price;
        return $data;
    }

    /**
     * 获取收货地址
     * @return array
     * @throws \Exception
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/29 16:17
     */
    private function getAddress(){
        if( $this->delivery_type == 1 ){ //快递
            $this->extract_shop_id = 0;
            $address = MemberAddress::findViewModel($this->address_id);
            if(empty($address)){
                //出错
                throw new \Exception('收货地址出错！');
            }
            return [
                'name'          => $address['name'],
                'phone'         => $address['phone'],
                'province_id'   => $address['province_id'],
                'city_id'       => $address['city_id'],
                'district_id'   => $address['district_id'],
                'detail'        => $address['detail'],
            ];
        }else{  //上门自取
            if(empty($this->name)){
                throw new \Exception('收货人不能为空！');
            }elseif(empty($this->phone)){
                throw new \Exception('收货电话不能为空！');
            }
            $this->address_id = 0;
            $address = Store::findViewModel($this->extract_shop_id);
            if(empty($address)){
                //出错
                throw new \Exception('上门自取地址出错！');
            }
            $this->city_id      = $address['city_id'];
            return [
                'name'          => $this->name,
                'phone'         => $this->phone,
                'province_id'   => $address['province_id'],
                'city_id'       => $address['city_id'],
                'district_id'   => $address['district_id'],
                'detail'        => $address['detail'],
            ];
        }

    }
    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/8 13:37
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if($this->isNewRecord){
            //事务
            $transaction = Yii::$app->db->beginTransaction();  // 创建事务
            try{
                //得出用户收货地址
                $address         = $this->getAddress();
                //算出商品价钱
                $goods           = $this->getOrderGoods();
                if(empty($goods) || empty($address)){
                    throw new \Exception('系统出错！');
                }

                if(parent::save($runValidation, $attributeNames)){
                    $order_id = Yii::$app->db->getLastInsertID();
                    //写入收货地址
                    $address['order_id'] = $order_id;
                    $_model = new OrderAddress();
                    $_model->setAttributes($address);
                    if(!$_model->save()){
                        //print_r($_model->getErrors());exit;
                        throw new \Exception('系统出错！');
                    }
                    // End 写入收货地址
                    /**
                     * 批量写入商品信息
                     */
                    //$model = new OrderGoods();
                    $data  = array();
                    foreach ($goods as $vo){
                        $vo['order_id'] = $order_id;
                        ksort($vo);
                        $data[] = $vo;
                       /* $_model = clone $model;
                        $_model->setAttributes($data);
                        if(!$_model->save()){
                            throw new \Exception('系统出错！');
                        }*/
                    }
                    //print_r($data);exit;
                    if (isset($data))
                    {
                        //按顺序
                        Yii::$app->db->createCommand()
                            ->batchInsert(OrderGoods::tableName(),[
                                'created_at',
                                'deduct_stock_type',
                                'goods_id',
                                'goods_weight',
                                'image_id',
                                'order_id',
                                'price',
                                'productcode',
                                'quantity',
                                'sku_attr_id',
                                'sku_attr_name',
                                'sku_id',
                                'status',
                                'title',
                                'total_price',
                                'updated_at',
                            ],
                                $data)
                            ->execute();
                    }

                    /**
                     * End 批量写入商品信息
                     */
                    $transaction->commit();  // 提交
                    return true;
                }else{

                    $transaction->rollBack();  // 回滚
                    return false;
                }
            }catch(\Exception $exception){

                $this->addError('error',$exception->getMessage());
                $transaction->rollBack();  // 回滚
                return false;
            }
            //ENd 事务
        }else{
            return parent::save($runValidation, $attributeNames); // TODO: Change the autogenerated stub
        }
    }




    /**
     * 订单关联商品
     * @return \yii\db\ActiveQuery
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/28 20:28
     */
    public function getGoods(){
        return $this->hasMany(OrderGoods::className(), ['order_id' => 'id']);
    }
    
    /**
     * 订单关闭
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/28 22:53
     */
    public function orderClose($id,$uid){
        $transaction = Yii::$app->db->beginTransaction();  // 创建事务
        try {

            $model = Order::find()
                ->where("id=:id")
                ->addParams([':id' => $id])
                ->select("id,uid,pay_status,order_status")
                ->one();
            if (empty($model)) {
                throw new \Exception('订单出错！');
            }elseif( $model->uid != $uid ){
                throw new \Exception('没有权限操作');
            }elseif ($model->order_status != 1) {
                throw new \Exception('订单出错！');
            }
            $volist = OrderGoods::find()
                ->where("order_id=:order_id")
                ->addParams([':order_id' => $model->id])
                ->select('goods_id,sku_id,quantity,deduct_stock_type')
                ->all();
            if (empty($volist)) {
                throw new \Exception('订单出错！');
            }
            foreach ($volist as $value) {
                if ($value['deduct_stock_type'] == 2) {
                    if ($model->pay_status == 1) {
                        self::RestoreStock($value['goods_id'], $value['sku_id'], $value['quantity']);//付款扣除
                    }
                } else {
                    self::RestoreStock($value['goods_id'], $value['sku_id'], $value['quantity']);//下订单扣除
                }
            }
            $model->order_status = 0;
            if (!$model->save()) {
                throw new \Exception('订单出错！');
            }
            $transaction->commit();  // 提交
            return true;
        }catch(\Exception $exception){

            $this->addError('error',$exception->getMessage());
            $transaction->rollBack();  // 回滚
            return false;
        }
    }
    /**
     * 订单支付
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/28 22:53
     */
    public function orderPayComplete($order_sn,$pay_type=1,$transaction_id=''){
        $transaction = Yii::$app->db->beginTransaction();  // 创建事务
        try {
            $model = Order::find()
                ->where("order_sn=:order_sn")
                ->addParams([':order_sn' => $order_sn])
                ->select("id,pay_type,pay_status,pay_time,transaction_id,order_status")
                ->one();
            if (empty($model)) {
                throw new \Exception('订单支付失败！');
            } elseif ($model->order_status == 0 || $model->pay_status != 0) {
                throw new \Exception('订单支付失败！');
            }
            $volist = OrderGoods::find()
                ->where("order_id=:order_id")
                ->addParams([':order_id' => $model->id])
                ->select('goods_id,sku_id,quantity,deduct_stock_type')
                ->all();
            if (empty($volist)) {
                throw new \Exception('订单支付失败！');
            }
            foreach ($volist as $value) {
                if ($value['deduct_stock_type'] == 2) {
                    self::SeductStock($value['sku_id'], $value['quantity']);
                }
            }
            $model->pay_type = $pay_type;
            $model->pay_status = 1;
            $model->transaction_id = $transaction_id;
            $model->pay_time = time();
            if (!$model->save()) {
                throw new \Exception('订单支付失败！');
            }
            $transaction->commit();  // 提交
            return true;
        }catch(\Exception $exception){
            $this->addError('error',$exception->getMessage());
            $transaction->rollBack();  // 回滚
            return false;
        }
    }




    /**
     * @param $model
     * @return string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date DateTime
     */
    public function getOrderStatus($model){
        if($model['order_status'] == 0){
            return '交易关闭';
        }elseif($model['pay_status'] == 0 && $model['order_status'] == 1){
            return '等待付款';
        }elseif($model['delivery_status'] == 0 && $model['order_status'] == 1){
            return '等待发货';
        }elseif($model['receipt_status'] == 0 && $model['order_status'] == 1){
            return '等待收货';
        }elseif($model['receipt_status'] == 1 && $model['order_status'] == 1){
            return '交易成功';
        }
    }


}
