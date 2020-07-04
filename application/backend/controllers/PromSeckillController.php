<?php

namespace backend\controllers;

use backend\models\Goods;
use backend\models\GoodsSku;
use Yii;
use backend\models\PromSeckill;
use backend\models\search\PromSeckillSearch as search;
use backend\components\NotFoundHttpException;
use yii\helpers\Json;


/**
 * MenuController implements the CRUD actions for Menu model.
 */
class PromSeckillController extends BaseController
{

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new search();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(false);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Menu model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PromSeckill();
        $model->scenario = 'create';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {

            return $this->render('create', [
                'model' => $model,
            ]);
        }
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

        $model->scenario = 'update';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            $goods = Goods::find()
                ->where("id=:id")
                ->addParams([':id'=>$model->goods_id])
                ->one();
            $skuList = null;
            if($goods->sku_type == 2){ //sku_list
                $skuList = GoodsSku::getSkuList($model->goods_id);
                $sku_price = Json::decode($model->sku_price,true);
                $data   = array();
                foreach ($skuList['sku_list'] as $key=> $val){
                    $val['form']['discount_price'] = $sku_price[$val['sku_id']]['discount_price'];
                    $data[$key] = $val;
                }
                $skuList['sku_list'] = $data;
                $skuList = Json::encode($skuList);
            }

            return $this->render('update', [
                'model' => $model,
                'goods' => $goods,
                'skuList' => $skuList
            ]);
        }
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        $id     = Yii::$app->request->post('id');
        if(empty($id))$this->error('请选择要操作的数据');
        $lists  = 0;
        $data   = (array) $id;
        foreach ($data as $val){
            $model = $this->findModel($val);
            $model  ->is_delete = 1;
            $lists  += $model->save();
        }
        if(!empty($lists)){
            $this->success('操作成功');
        }else{
            $this->error('操作失败');
        }
    }

  /*
  * 更新状态
  */
    public function actionStatus(){
        $id     = Yii::$app->request->post('id');
        $value  = Yii::$app->request->post('value');
        $name   = Yii::$app->request->post('name');
        if(empty($id))$this->error('请选择要操作的数据');
        if(!isset($name))$this->error('缺少字段参数');
        if(!isset($value))$this->error('缺少数据参数');
        $data   = (array) $id;
        $transaction = Yii::$app->db->beginTransaction();  // 创建事务
        try{
            $newtime = time();
            foreach ($data as $val){
                $model  = $this->findModel($val);
                if( $model->status != -1 && $model->status != 0 ){
                    if( $value == 0){
                        $msg = '开始活动';
                    }else{
                        $msg = '取消活动';
                    }
                    throw new \Exception('活动进行中/活动结束无法【'.$msg.'】');
                }elseif($value==0){
                    //判断开始时间
                    if( $model->start_time<$newtime){
                        throw new \Exception('活动开始时间必须大于当前时间');
                    }
                }
                $model->status = $value;
                if(!$model->save()){
                    $error =current( $model->getFirstErrors() );
                    throw new \Exception($error);
                }
            }
            $transaction->commit();  // 提交
            $this->success('操作成功');
        }catch(\Exception $exception){
            $message = $exception->getMessage();
            $transaction->rollBack();  // 回滚
            $this->error($message);
        }

    }
    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PromSeckill the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PromSeckill::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param $goods_id
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/3/5 22:20
     */
    public function actionSku($goods_id){
        $data = GoodsSku::getSkuList($goods_id);
        if($data['sku_list'] == null){
            $model = GoodsSku::find()
                ->where("goods_id=:goods_id")
                ->addParams([':goods_id'=>$goods_id])
                ->select("id")
                ->one();
            $this->ajaxReturn(['status'=>false,'sku_id'=>$model->id]);
        }else{
            $this->ajaxReturn(['status'=>true,'sku_id'=>0,'data'=>$data]);
        }

    }
}
