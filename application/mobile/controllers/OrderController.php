<?php
namespace mobile\controllers;
use common\components\Func;
use mobile\models\Goods;
use mobile\models\GoodsAttrValue;
use mobile\models\GoodsSku;
use mobile\models\Order;
use mobile\models\OrderRefund;
use mobile\models\search\GoodsSearch;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * Site controller
 */
class OrderController extends BaseController
{
    public $uid = 1;
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Order();
        $volist = $model::find()
            ->alias('a')
            ->where("uid=:uid")
            ->addParams([':uid'=>1])
            ->joinWith('goods')
            ->select("a.id,a.order_sn,a.pay_price,a.total_price,a.pay_status,a.delivery_status,a.order_status,a.receipt_status")
            ->asArray()
            ->orderBy(['id'=>SORT_DESC])
            ->all();


       return $this->render('index',[
            'model'     => $model,
            'volist'    => $volist,
       ]);
    }

    /**
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/29 15:22
     */
    public function actionClose($id){
        //Goods::updateAll(['view'=>['stock'=>$quantity]],['id'=>$goods_id]);
        //Goods::updateAllCounters(['view'=>5], ['id' => 1]);exit;
        $model = new Order();
        if( !$model->orderClose($id,$this->uid) ){
            print_r($model->getFirstErrors());
        }
    }

    /**
     * @param $id
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/29 20:53
     */
    public function actionDelete($id){

    }

    public function actionPay($id){
        $order_sn = '20200301965118566250';
        $transaction_id = '3452332ss132132324';
        $model = new Order();
        if( !$model->orderPayComplete($order_sn,1,$transaction_id) ){
            print_r($model->getFirstErrors());
        }


    }

    /**
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date DateTime
     */
    public function actionRefund(){
        $data = [
            'order_goods_id'    =>  6,
            'type'              =>  2,
            'refund_type'       =>  1,
            'refund_money'      =>  '2699.00',
            'refund_explain'    =>  '43224',
            'image'             =>  '1',
        ];
        $model = new OrderRefund();
        if( !$model->refundGoods($data) ){
            print_r($model->getFirstErrors());
        }
        /*$refund_explain = '';
        $order_id       = '1';
        $model = new OrderRefund();
        if( !$model->OrderApplyRefund($order_id,1,$refund_explain) ){
            print_r($model->getFirstErrors());
        }*/

    }
    public function actionRefundGoods(){
        $data = [
            'id'            =>  9,
            'express_name'  =>  '圆通快递',
            'express_sn'    =>  '231342132132132',
        ];
        $model = new OrderRefund();
        if( !$model->refundGoodsExpress($data) ){
            print_r($model->getFirstErrors());
        }
    }
}
