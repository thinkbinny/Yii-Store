<?php
namespace backend\controllers;

use backend\models\UploadFile;
use backend\models\UploadGroup;
use backend\models\search\UploadGroupSearch;
use backend\components\NotFoundHttpException;
use Yii;


class FilesGroupController extends BaseController {

    /**
     * 模型管理首页
     * @author Thinkbinny
     */
    public function actionIndex(){

        $searchModel = new UploadGroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$dataProvider->setSort(false); //禁止表头排序
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Creates a new Admin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new UploadGroup();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if(Yii::$app->request->isAjax){
                $data = [
                  'status'=>true,
                  'message'=>'添加成功',
                  'id'=>$model->id,
                ];
                $this->ajaxReturn($data);exit;
            }else{
                return $this->redirect(['index']);
            }
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
        UploadFile::updateAll(['group_id'=>0],'group_id=:group_id',[':group_id'=>$id]);
        return $this->redirect(['index']);
    }
    /**
     * Finds the Admin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UploadGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UploadGroup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}