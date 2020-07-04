<?php

namespace backend\controllers;

use common\components\Func;
use Yii;
use backend\models\Order;
use backend\models\search\OrderSearch;
use backend\components\NotFoundHttpException;



/**
 * MenuController implements the CRUD actions for Menu model.
 */
class OrderController extends BaseController
{

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 待发货订单
     * @return array|string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/12 13:35
     */
    public function actionDeliveryList()
    {
        $searchModel = new OrderSearch();
        $searchModel->pay_status      = 1;
        $searchModel->delivery_status = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 待收货订单
     * @return array|string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/12 13:35
     */
    public function actionReceiptList()
    {
        $searchModel = new OrderSearch();
        $searchModel->delivery_status = 1;
        $searchModel->receipt_status = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 待支付订单
     * @return array|string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/12 13:34
     */
    public function actionPayList()
    {
        $searchModel = new OrderSearch();
        $searchModel->pay_status = 0;
        $searchModel->order_status = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 完成订单
     * @return array|string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/12 13:32
     */
    public function actionCompleteList()
    {
        $searchModel = new OrderSearch();
        $searchModel->receipt_status = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * 关闭订单
     * @return array|string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/12 13:32
     */
    public function actionCancelList()
    {
        $searchModel = new OrderSearch();
        $searchModel->order_status = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update_price';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {

            return $this->render('update', [
                'model' => $model,

            ]);
        }
    }


    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/5 20:40
     */
    public function actionView($id){
        $this->layout = '_main';
        $model = Order::find()
            ->alias('a')
            ->where("a.id=:id")
            ->addParams([':id'=>$id])
            ->joinWith('goods')
            ->joinWith('address')
            ->one();

        return $this->render('view',['model'=>$model]);
    }

    /**
     * 导出订单
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/11 22:12
     */
    public function actionExport(){

        $headlist = [
          '订单编号',
          '日期',
          '收货人',
          '电话',
          '收货地址',
          '订单金额',
          '实际支付',
          '支付方式',
          '支付状态',
          '发货状态',
          '商品信息',
        ];
        $time = strtotime(date('Y-m-d',strtotime("-7 day")));
        $volist = Order::find()
            ->alias('a')
            ->where("a.created_at>:created_at")
            ->addParams([':created_at'=>$time])
            ->joinWith('goods')
            ->joinWith('address')
            ->all();
        $data = array();
        foreach ($volist as $model){
            $goodsInfo = '';
            $i = 1;
            foreach ($model->goods as $goods){
                $attr = $model -> getSkuAttrNameText($goods['sku_attr_name']);
            $goodsInfo .= <<<GOODS
{$i}.{$goods->title}
商品规格：{$attr} 
购买数量：{$goods->amount}
商品总价：{$goods->total_price}元

GOODS;
            $i++;
            }

            $data[] = [
                $model->order_sn,
                date('Y-m-d H:i:s',$model->created_at),
                $model->address->name,
                $model->address->phone,
                $model->addressText,
                $model->total_price,
                $model->pay_price,
                strip_tags($model->payTypeText),
                strip_tags($model->payStatusText),
                strip_tags($model->deliveryStatusText),
                $goodsInfo,
            ];
        }
        $fileName = 'order_'.date('Ymd');
        Func::setCsv($fileName,$headlist,$data);

    }
    /**
     * 发货 delivery
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/11 22:14
     */
    public function actionDelivery($id){
        $this->layout = '_main_ajax';
        $model = $this->findModel($id);
        $model->scenario = 'delivery';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {

            return $this->render('delivery', [
                'model' => $model,

            ]);
        }
    }
    /**
     * 批量发货
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/11 22:14
     */
    public function actionBatchdelivery(){

        if(Yii::$app->request->get('type') == 'deliverytpl'){
            $headlist = [
                '订单号',
                '物流单号',
            ];
            $fileName = 'deliverytpl';
            Func::setCsv($fileName,$headlist);
        }else{
            $this->layout = '_main_ajax';
            $model = new Order();
            $model->scenario = 'batchdelivery';
            if ($model->load(Yii::$app->request->post()) && $model->batchdelivery()) {
                return $this->redirect(['index']);
            } else {

                return $this->render('batchdelivery', [
                    'model' => $model,

                ]);
            }
        }
    }
}
