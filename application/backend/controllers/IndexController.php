<?php
namespace backend\controllers;
use backend\models\Goods;
use backend\models\Member;

use backend\models\Order;

use common\models\Tixian;
use Yii;




class IndexController extends BaseController {

    public function actionIndex(){
        //框架
        $statistics = array();
        $statistics['user'] = Member::find()->count();
        $statistics['goods'] = Goods::find()->where("is_delete=0")->count();
        $statistics['order'] = Order::find()->where("pay_status=0")->count();
        $statistics['order_pay'] = Order::find()->where("pay_status=1 AND delivery_status=0")->count();
        //$a= Tixian::ApplyTixian(2,15); print_r($a);exit;
        return $this->render('index',[
            "statistics" => $statistics
        ]);
    }

    /**/
    public function actionIframe(){
        //$this->layout = false;
        return $this->render('_iframe');
    }
}


