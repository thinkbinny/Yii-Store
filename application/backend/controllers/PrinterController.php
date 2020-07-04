<?php

namespace backend\controllers;

use backend\models\Config;
use Yii;
use backend\models\Printer;
use backend\models\search\PrinterSearch;
use backend\components\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;


/**
 * MenuController implements the CRUD actions for Menu model.
 */
class PrinterController extends BaseController
{

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new PrinterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Printer();

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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {

            return $this->render('update', [
                'model' => $model,

            ]);
        }
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model  = $this->findModel($id);
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Printer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Printer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/5 20:40
     */
    public function actionSetting(){
        $data = Printer::find()
            ->where("status=:status")
            ->addParams([':status'=>1])
            ->select("id,name")
            ->asArray()
            ->all();
        $PrinterList = ArrayHelper::map($data,'id','name');
        $id = 	9;
        $model           = Config::findOne($id);
        $model->scenario = 'setup';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['setting','id'=>$id]);
        } else {
            $model->data = Json::decode($model->data,true);//print_r($model->data);exit;
            return $this->render('setting', [
                'model' => $model,
                'PrinterList'=>$PrinterList
            ]);
        }


    }
}
