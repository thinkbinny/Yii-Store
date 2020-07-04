<?php

namespace backend\controllers;

use Yii;
use backend\models\GoodsAttr;
use backend\models\search\GoodsAttrSearch;
use backend\components\NotFoundHttpException;

use yii\helpers\ArrayHelper;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class GoodsAttrController extends BaseController
{

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new GoodsAttrSearch();
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
        $model = new GoodsAttr();

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
        $model->is_delete = 1;
        $model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GoodsAttr the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GoodsAttr::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     * @return array|string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/18 15:48
     */
    public function actionSelect($model_type){
        $this->layout = '_main';
        //$searchModel = new GoodsAttrSearch();
        $model = new GoodsAttr();
        $volist = $model::find()
            ->where("status=:status and model_type=:model_type")
            ->addParams([':status'=>1,':model_type'=>$model_type])
            ->all();

        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$dataProvider->setSort(false); //禁止表头排序
        return $this->render('_select', [
            //'searchModel' => $searchModel,
            //'dataProvider' => $dataProvider,
            'model'     =>$model,
            'volist'    =>$volist,
            'model_type'=>$model_type,
        ]);
    }
}
