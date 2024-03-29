<?php
namespace backend\models;

use common\components\Func;
use common\models\OrderForm;
use Yii;

use common\models\OrderRefund as common;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Response;


class OrderRefund extends common
{
    public $address_id;
    public $verify;
    public function rules()
    {
        return [
            [['verify'], 'required','message'=>'请选择{attribute}','on'=>['verify']],
            ['verify', 'in', 'range' => [0,1],'message'=>'请选择{attribute}','on'=>['verify']],
            [['address_id'], 'integer'],
            [['remark'], 'string', 'max' => 250],
            [['operation'], 'string', 'max' => 500],
        ];
    }
    public function scenarios()
    {
        $scenarios =  parent::scenarios(); // TODO: Change the autogenerated stub
        $scenarios['verify'] = ['verify','remark','operation'];
        $scenarios['remark'] = ['remark'];
        return $scenarios;
    }

    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/3/4 13:39
     */
    public function attributeLabels(){
        $attributeLabels = parent::attributeLabels();
        $attributeLabels['verify'] = '处理状态';
        return $attributeLabels;
    }
    /**关联商品信息**/
    public function getGoods(){
        return $this->hasOne(OrderGoods::className(), ['id' => 'order_goods_id']);
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
     * 获取用户昵称
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/10 22:13
     */
    public function getNicknameText(){
        return Func::get_nickname($this->uid);
    }

    /**
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/3/3 23:11
     */
    public function getReturnAddress(){
       $data    = array();
       $volist  = ReturnAddress::getAddress();
       foreach ($volist as $key=> $value){
           $data[$key] = $value['name'].' '.$value['phone'].' （'.$value['detail'].'）';
       }
       return $data;
    }
    /**
     * @var array
     */
    public static $type = [
        1 => '仅退款',      //用户已支付 未发货或 用户收到货出现问题，退款一部分
        2 => '退货退款',
        3 => '换货',
    ];

    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/15 22:05
     */
    public function getType(){
        return self::$type;
    }

    /**
     * @return mixed
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/15 22:05
     */
    public function getTypeText(){
        $data = self::$type;
        return $data[$this->type];
    }

    /**
     * 售后状态
     * @var array
     */
    public static $status = [
         '-2' => '已取消',
         '-1' => '已拒绝',//'审核未通过',
         '0' => '待审核',
         '1' => '审核通过',
         '2' => '已发货',
         '3' => '已完成',
    ];
    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>S
     * @Date 2020/2/15 22:05
     */
    public function getStatus(){
        return self::$status;
    }

    /**
     * @return mixed
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/15 22:05
     */
    public function getStatusText(){
        $data = self::$status;
        return $data[$this->status];
    }

    public function getOrderGoodsText(){
        $model = OrderGoods::find();
        if(!empty($this->order_goods_id)){
            $model->where("id=:id");
            $model->addParams([':id'=>$this->order_goods_id]);
        }else{
            $model->where("order_id=:order_id");
            $model->addParams([':order_id'=>$this->order_id]);
        }
        $model->select('id,order_id,goods_id,title,image_id,sku_attr_name,price,quantity,total_price,status');
        $volist = $model->all();
        return $volist;

    }
    public function afterValidate()
    {
        if($this->status == 0){

            if($this->type == 2 || $this->type == 3){
                if($this->verify == 1){
                    if(empty($this->address_id)){
                        $this->addError('address_id','请选择收货地址');
                        return false;
                    }
                    $data = ReturnAddress::getAddress();
                    if(!isset($data[$this->address_id])){
                        $this->addError('address_id','请选择收货地址');
                        return false;
                    }
                    $data = $data[$this->address_id];
                    $operation = [
                      'name'    =>$data['name'],
                      'phone'   =>$data['phone'],
                      'detail'  =>$data['detail'],
                    ];
                    $this->operation = Json::encode($operation);
                }
            }
        }
        parent::afterValidate(); // TODO: Change the autogenerated stub
    }

    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/3/4 13:40
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        //用户已发货
        if($this->status == 0) {
            if( $this->model_type == 1 ){ //订单退款
                $transaction = Yii::$app->db->beginTransaction();  // 创建事务
                try {

                    if($this->verify == 1){
                        throw new \Exception('请选择 退款方式 进行直接退款操作');
                        /*$model = new OrderForm();
                        $this->status = 3;
                        if( !$model->orderClose($this->order_id,$this->uid) ){
                            $error = current( $model->getFirstErrors() );
                            throw new \Exception($error);
                        }*/
                    }
                    if( !parent::save($runValidation, $attributeNames) ){
                        $transaction->rollBack();
                        return false;
                    }
                    $transaction->commit();  // 提交
                    return true;
                }catch(\Exception $exception){
                    header('content-type:application/'.Response::FORMAT_JSON.';charset=utf-8');
                    $message = $exception->getMessage();
                    $transaction->rollBack();  // 回滚
                    $data = ['status'=>false,'message'=>$message];
                    exit(json_encode($data));
                }
                //orderClose

            }elseif($this->model_type == 2){
                //已发货 商品售后

                $transaction = Yii::$app->db->beginTransaction();  // 创建事务
                try {
                    if($this->verify == 0){
                        $this->status = -1;
                        if( !OrderGoods::updateAll(['status'=>1],'id=:id',[':id'=>$this->order_goods_id]) ){
                            throw new \Exception('系统出错');
                        }
                    }else{
                        $this->status = 1;
                    }
                    if( !parent::save($runValidation, $attributeNames) ){
                        $transaction->rollBack();
                        return false;
                    }
                    $transaction->commit();  // 提交
                    return true;
                }catch(\Exception $exception){
                    header('content-type:application/'.Response::FORMAT_JSON.';charset=utf-8');
                    $message = $exception->getMessage();
                    $transaction->rollBack();  // 回滚
                    $data = ['status'=>false,'message'=>$message];
                    exit(json_encode($data));
                }

            }
        }else{
            return parent::save($runValidation, $attributeNames); // TODO: Change the autogenerated stub
        }
    }

}
