<?php
namespace backend\controllers;

use Yii;
use backend\models\Config;
use backend\models\search\ConfigSearch;
use backend\components\NotFoundHttpException;
use yii\helpers\Json;

/**
 * ConfigController implements the CRUD actions for Config model.
 */
class ConfigController extends BaseController
{

    /**
     *
     */
    public function actionIndex() {
        $searchModel = new ConfigSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionSetup($id){

        $model           = $this->findModel($id);
        $model->scenario = 'setup';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['setup','id'=>$id]);
        } else {
            $model->data = Json::decode($model->data,true);
            return $this->render('setup', [
                'model' => $model,
            ]);
        }

    }


    /**
     * Creates a new Config model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Config();
        $model->scenario = 'create';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index','keyid'=>'all']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Config model.
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
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Config model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'delete';
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Config model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Config the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Config::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 商城设置
     * @return $this|array|string|\yii\web\Response
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/17 20:52
     */
    public function actionMall(){
        $id = 	6;
        $model           = $this->findModel($id);
        $model->scenario = 'setup';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['mall','id'=>$id]);
        } else {
            $model->data = Json::decode($model->data,true);
            return $this->render('mall', [
                'model' => $model,
            ]);
        }

    }

    public function actionSms(){
        $id = 	7;
        $model           = $this->findModel($id);
        $model->scenario = 'setup';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['sms','id'=>$id]);
        } else {
            $model->data = Json::decode($model->data,true);
            return $this->render('sms', [
                'model' => $model,
            ]);
        }
    }
}
