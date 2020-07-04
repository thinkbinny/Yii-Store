<?php

namespace backend\controllers;

use backend\models\Config;
use Yii;
use backend\models\WxUser;
use backend\models\search\WxUserSearch;
use backend\components\NotFoundHttpException;


/**
 * MenuController implements the CRUD actions for Menu model.
 */
class WxController extends BaseController
{

    /**
     * @param $model
     * @return string
     * 公众号配置
     */
    protected function wxConfig($model,$type='mp'){
        if(Yii::$app->request->isPost) {
            if(empty($model)) {
                $model = new Config();
                $model->keyid = 'weixin';
            }
            $form        = Yii::$app->request->post('form');
            if(!empty($model->data)){
                $oldForm   = json_decode($model->data, true);
                $form      = array_merge($oldForm,[$type=>$form]);
            }else{
                $form       = [$type=>$form];
            }
            $model->data = json_encode($form);
            if($model->save()){
                $this->success(Yii::t('backend','Successful Operation'));
            }else{
                $this->error(Yii::t('backend','Operation Failed'));
            }
        }
        if(!isset($model->data)){
            $formParams = [];
        } else {
            $formParams = json_decode($model->data, true);
            $formParams = $formParams[$type];
        }
        return $this->render('config-'.$type, [
            'formParams' => $formParams,
        ]);
    }

    public function actionMpConfig(){
        $keyid = 'mp';
        $model = Config::find()->where('keyid=:keyid')->addParams([':keyid'=>'weixin'])->one();

        return $this->wxConfig($model,$keyid);
    }
    public function actionMiniConfig(){
        $keyid = 'mini';
        $model = Config::find()->where('keyid=:keyid')->addParams([':keyid'=>'weixin'])->one();

        return $this->wxConfig($model,$keyid);
    }
    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new WxUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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
        $model = new WxUser();

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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WxUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WxUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
