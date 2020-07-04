<?php
namespace common\models;
use yii\behaviors\TimestampBehavior;
use Yii;



class Coupon extends \yii\db\ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name','trim'],
            [['name'],'required'],
            [['type'],'required','message'=>'请选择{attribute}'],
            [['price','discount','min_price'],'required'],
            [['expire_type'],'required','message'=>'请选择{attribute}'],
            [['expire_day','start_time','end_time','amount','limit_amount','receive','sort'],'required'],
            [['id','type','expire_type','expire_day','start_time','end_time','amount','limit_amount','sort','status','created_at','updated_at'], 'integer'],
            [['name',], 'string', 'max' => 50],
            [['price','min_price','discount'], 'double', ],
            [['sort'], 'default', 'value' => 50],
            [['receive'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => 1],
            [['type','expire_type'], 'in', 'range' => [1, 2]],
            [['status'], 'in', 'range' => [0, 1]],
        ];
    }

    /**
     * @inheritdoc
     */

    public function attributeLabels()
    {
        return [
            'id'                => '优惠ID',
            'name'              => '优惠券名称',
            'type'              => '优惠券类型',//（1满减券、2折扣券）
            'price'             => '减免金额',
            'discount'          => '折扣率',
            'min_price'         => '最低消费金额',
            'expire_type'       => '到期类型', //expire_type
            'expire_day'        => '有效天数',
            'start_time'        => '开始时间',
            'end_time'          => '结束时间',
            'amount'            => '发放总数量',
            'limit_amount'      => '限领数量',
            'receive'           => '领取数量',
            'sort'              => '顺序',
            'status'            => '状态',//（1开启0关闭）
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


    /**
     * @var array
     */
    public static $type = [
        1 => '满减券',
        2 => '折扣券',
    ];
    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date DateTime
     */
    public function getType(){
        return self::$type;
    }

    /**
     * @return mixed
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/16 14:47
     */
    public function getTypeText(){
        return self::$type[$this->type];
    }

    /**
     * 领取优惠券
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/18 19:19
     *$result['message'] = '每个用户最多可以领取'.$vo['limit_amount'].'次'; 领取成功
     */
    public static function getReceive($coupon_id,$uid,$signup=false){
        $transaction = Yii::$app->db->beginTransaction();  // 创建事务
        try{
            $model = self::find()
                ->where("id=:id")
                ->addParams([':id'=>$coupon_id])
                ->one();
            if(empty($model)){
                throw new \Exception('优惠券不存在！');
            }elseif($model->status !=1){
                throw new \Exception('优惠券已下架！');
            }elseif($model->amount <= $model->receive){
                throw new \Exception('优惠券已领完！');
            }
            $_model = new CouponReceive();
            $_count = $_model::find()
                ->where("uid=:uid and coupon_id=:coupon_id")
                ->addParams([':uid'=>$uid,':coupon_id'=>$coupon_id])
                ->count();
            if($_count >= $model->limit_amount){
                throw new \Exception('每个用户最多可以领取'.$model->limit_amount.'次');
            }
            $_model->scenario = 'receive';
            if($model->expire_type == 1){
                $_model->start_time   = time();
                $_model->end_time     = strtotime("+{$model->expire_day} day");
            }else{
                $_model->start_time   = $model->start_time;
                $_model->end_time     = $model->end_time;
            }
            $_model->coupon_id        = $model->id;
            $_model->name             = $model->name;;
            $_model->type             = $model->type;;
            $_model->discount         = $model->discount;;
            $_model->price            = $model->price;;
            $_model->min_price        = $model->min_price;;
            if(!$_model->save()){
                throw new \Exception(current($_model->getFirstErrors()));
            }
            $model->receive = $model->receive + 1;
            if(!$model->save()){
                throw new \Exception(current($model->getFirstErrors()));
            }
            $transaction->commit();
            return ['status'=>true,'message'=>'领取成功'];
        }catch(\Exception $exception){
            $message = $exception->getMessage();
            $transaction->rollBack();  // 回滚
            $data = ['status'=>false,'message'=>$message];
            return $data;
        }
    }
    /**
     *使用优惠券
     */
    public static function UpdateUse($id,$price){
        try {
            $model = CouponReceive::findOne($id);
            if (empty($model)) {
                throw new \Exception('优惠券不存在');
            }
            $newTime = time();
            if (empty($price)) {
                throw new \Exception('支付金额必须大于0元');
            } elseif ($model->status == 2) {
                throw new \Exception('优惠券已使用');
            } elseif ($model->status != 1) {
                throw new \Exception('优惠券已过期');
            } elseif ($model->start_time > $newTime || $model->end_time < $newTime) {
                throw new \Exception('领取失败，领取时间已过期');
            } elseif ($model->min_price > $price) {
                throw new \Exception("优惠券必须满{$model->min_price}元才能使用");
            }
            if ($model->type == 1) {
                //满减
                $price = bcsub($price, $model->price, 2);
            } else {
                //满打折
                $price = bcmul($price, $model->discount, 2);
            }

            $model->scenario = 'status';
            $model->status   = 2;
            if (!$model->save()) {
                throw new \Exception(current($model->getFirstErrors()));
            }
            return [
                'discount_price' => $price,//折扣后的价钱
                'status'         =>true
            ];
        }catch(\Exception $exception){
            $message = $exception->getMessage();
            $data = ['status'=>false,'message'=>$message];
            return $data;
        }
    }

    /**
     * 订单关闭还原
     * @param $id
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/18 22:18
     */
    public static function UpdateOrderClose($id){
        try {
            $model = CouponReceive::findOne($id);
            if (empty($model)) {
                throw new \Exception('优惠券不存在');
            }
            $newTime = time();
            if ($model->start_time > $newTime || $model->end_time < $newTime) {
                $model->status   = 0;
            }else{
                $model->status   = 1;
            }
            $model->scenario = 'status';
            if (!$model->save()) {
                throw new \Exception(current($model->getFirstErrors()));
            }
            return [
                'message'   => '操作成功',
                'status'    => true
            ];
        }catch(\Exception $exception){
            $message = $exception->getMessage();
            $data = ['status'=>false,'message'=>$message];
            return $data;
        }
    }

    /**更新过期优惠券
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/18 22:25
     */
    public static function UpdateExpire(){
        $newTime = time();
        CouponReceive::updateAll([
            'status'    =>0,
            'updated_at'=>$newTime
        ],
            'status=:status and end_time<:end_time',
            [':status'=>1,':end_time'=>$newTime]);
    }

}
