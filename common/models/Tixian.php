<?php
namespace common\models;
use yii\behaviors\TimestampBehavior;
use Yii;



class Tixian extends \yii\db\ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tixian}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {

        return [
            [['uid','realname','open_account','account','type','money'], 'required'],
            [['uid','type','checker_time','accountant_time','created_at','updated_at'], 'integer'],
            ['status', 'in', 'range' => [-1,0,1,2]],
            [['realname','account','checker','accountant'], 'string', 'max' => 50],
            [['open_account'], 'string', 'max' => 100],
            [['money'], 'double'],
            [['checker','accountant'], 'default', 'value' => ''],
            [['checker_time','accountant_time'], 'default', 'value' => 0],
        ];

       /* return [
            [['id','uid','goods_id','sku_id','quantity','fengxiao_uid','created_at','updated_at'], 'integer'],
        ];*/
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => 'ID',
            'uid'               => '提现用户',
            'realname'          => '真实姓名',
            'open_account'      => '开户账号',//（如：支付宝，中国农业银行）
            'account'           => '提现账号',
            'type'              => '提现类型',//（1支付宝,2银行卡）
            'money'             => '提现金额',
            'checker'           => '审核人',
            'checker_time'      => '审核时间',
            'accountant'        => '财务人',
            'accountant_time'   => '打款时间',
            'status'            => '状态',//(0待审核，1审核通过，2已打款，3驳回)
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
     * @param $uid
     * @param $money
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/3/8 13:10
     */
    public static function  ApplyTixian($uid,$money){
        if(empty($uid)){
            return [
              'status' =>false,
              'message'=>'用户不能为空',
            ];
        }elseif(is_null($money)){
            return [
                'status' =>false,
                'message'=>'金额不能为空',
            ];
        }elseif($money<=0){
            return [
                'status' =>false,
                'message'=>'金额必须大于0.01元',
            ];
        }
        $transaction = Yii::$app->db->beginTransaction();  // 创建事务
        try{
            $model = new Member();
            $member = $model::find()
                ->where("uid=:uid")
                ->addParams([':uid'=>$uid])
                ->one();
            if(empty($member)){
                throw new \Exception('用户被禁止或不存在！');
            }elseif($member->status != $model::STATUS_ACTIVE){
                throw new \Exception('用户已被禁用！无法提现');
            }elseif( $member->money < $money){
                throw new \Exception('用户金额不足');
            }
            $data = $model::setProperty($uid,'dec',1,$money,'用户申请提款');
            if($data['status']==false){
                throw new \Exception($data['message']);
            }
            //store
            $store = Store::find()
                ->where("uid=:uid")
                ->addParams([':uid'=>$uid])
                ->select("tixian_type,open_account,realname,account")
                ->asArray()
                ->one();
            if(empty($store)){
                throw new \Exception('系统出错');
            }
            $tixian = new self();
            $tixian->uid            = $uid;
            $tixian->realname       = $store['realname'];
            $tixian->open_account   = $store['open_account'];
            $tixian->account        = $store['account'];
            $tixian->type           = $store['tixian_type'];
            $tixian->money          = $money;
            if( !$tixian->save() ){
                $error = current( $tixian->getFirstErrors() );
                throw new \Exception($error);
            }
            $transaction->commit();  // 提交
            return true;
        }catch(\Exception $exception){
            $message = $exception->getMessage();
            $transaction->rollBack();  // 回滚
            $data = ['status'=>false,'message'=>$message];
            return $data;

        }


    }

}
