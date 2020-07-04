<?php

namespace backend\controllers;

use Yii;
use backend\models\Category;
use backend\models\search\CategorySearch;
use backend\components\NotFoundHttpException;
use common\libs\Tree;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class CategoryController extends BaseController
{

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new CategorySearch();
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
        $model = new Category();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            $model->pid = Yii::$app->request->get('pid', 0);
            $arr = Category::find()->asArray()->all();
            $treeObj = new Tree($arr);
            return $this->render('create', [
                'model' => $model,
                'treeArr' => $treeObj->getTree(0,'id','pid','title'),
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
            $arr = Category::find()->asArray()->all();
            $treeObj = new Tree($arr);
            return $this->render('update', [
                'model' => $model,
                'treeArr' => $treeObj->getTree(0,'id','pid','title'),
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
        //查看是否有子分类
        $xia = $model->find()->where('pid=:pid')->addParams([':pid'=>$model->id])->count();
        if(!empty($xia)){
            Yii::$app->session->setFlash('error', '请先删除该分类下的子分类');
            return $this->redirect(['index']);
        }

        if($model->model_id==3){
            //删除单页
            \backend\models\article\SinglePage::deleteAll(['id'=>$model->model_id]);
        }else{
            //先删除文章内容在来删除
            $count = \backend\models\article\Document::find()
                ->where('category_id=:category_id')
                ->addParams([':category_id'=>$model->id])
                ->count();
            if(!empty($count)){
                Yii::$app->session->setFlash('error', '请先删除该分类下的文章（包含回收站）');
                return $this->redirect(['index']);
            }
        }
        /**
         * 必须查出下面是否有数据才可以删除
         */
        //$this->findModel($id)->update();
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
