<?php

namespace backend\controllers;

use Yii;
use backend\models\AppsMethod;
use backend\models\search\AppsMethodSearch;
use backend\components\NotFoundHttpException;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class AppsMethodController extends BaseController
{

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->request->isPost) {
            $sorts = Yii::$app->request->post('sort');
            if (!empty($sorts)) {
                foreach ($sorts as $id => $v) {
                    $model = AppsMethod::findOne($id);
                    $model->sort = $v;
                    $model->save();
                }
                Yii::$app->session->setFlash('success', '操作成功');
            }
        }
        $searchModel = new AppsMethodSearch();
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
        $model = new AppsMethod();

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
     * Changestatus an existing Model model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionChangestatus()
    {

        if(Yii::$app->request->isPost){
            $val = Yii::$app->request->get('val');
            $ids = Yii::$app->request->post('id');
            if(isset($ids))
                $ids =  is_array($ids) ? $ids : [$ids];
            if ( empty($ids) ) {
                $this->error('请选择要操作的数据!');
            }
            $lists = AppsMethod::updateAll(['status'=>$val],['in','id',$ids]);

            if($lists){
                if($val==1){
                    $this->success('已开启','',['method'=>'open']);
                }else{
                    $this->success('已禁止','',['method'=>'forbid']);
                }
                exit;
            }else{
                $this->error('操作失败');
            }
        }else{
            $this->UpdateSwitch(new AppsMethod(),'status');
        }
    }
    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AppsMethod the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AppsMethod::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}