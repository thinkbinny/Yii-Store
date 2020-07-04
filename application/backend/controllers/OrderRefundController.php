<?php

namespace backend\controllers;

use backend\models\Member;
use common\components\Func;
use Yii;
use backend\models\OrderRefund as model;
use backend\models\search\OrderRefundSearch as search;
use backend\components\NotFoundHttpException;



/**
 * MenuController implements the CRUD actions for Menu model.
 */
class OrderRefundController extends BaseController
{

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new search();
        $searchModel->model_type = 2;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionOrder()
    {
        $searchModel = new search();
        $searchModel->model_type = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('order', [
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
    public function actionView($id)
    {
        $this->layout = '_main_ajax';
        $model = $this->findModel($id);
        if($model->status == 0){
            $model->scenario = 'verify';
        }else{
            $model->scenario = 'remark';
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {

            return $this->render('view', [
                'model' => $model,

            ]);
        }
    }

    public function actionOperation($id)
    {
        $this->layout = '_main_ajax';
        $model = $this->findModel($id);

        if( Yii::$app->request->isPost ) {

            $transaction = Yii::$app->db->beginTransaction();  // 创建事务
            try {
                $member = new Member();
                $data = $member::setProperty($model->uid,'inc',4,$model->refund_deposit);
                if($data['status'] == false){
                    throw new \Exception($data['message']);
                }
                if( !$model->OrderRefund($id) ){
                    $error = current($model->getFirstErrors());
                    throw new \Exception($error);
                }
                $transaction->commit();  // 提交
                return $this->redirect(['index']);
            }catch(\Exception $exception){
                $transaction->rollBack();  // 回滚
                return $this->render('operation', [
                    'model' => $model,

                ]);
            }
        }else{
            return $this->render('operation', [
                'model' => $model,

            ]);
        }

    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return model the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = model::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }



}
