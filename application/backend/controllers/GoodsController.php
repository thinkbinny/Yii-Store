<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use Yii;
use backend\models\Goods;
use backend\models\search\GoodsSearch;
use backend\components\NotFoundHttpException;

use yii\helpers\ArrayHelper;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class GoodsController extends BaseController
{

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new GoodsSearch();
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
        $model = new Goods();

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
        $model->scenario = 'delete';
        $model->is_delete = 1;
        $model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Goods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Goods::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
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
        $lists  = 0;
        $data   = (array) $id;
        foreach ($data as $val){
            $model  = $this->findModel($val);
            $model->scenario = 'status';
            $model->{$name} = $value;
            $lists  += $model->save();
        }
        if(!empty($lists)){
            $this->success('操作成功');
        }else{
            $this->error('操作失败');
        }
    }

    public function actionSelect(){
        $this->layout   = '_main_ajax';
        $searchModel    = new GoodsSearch();
        $dataProvider   = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('_select', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}
