<?php

namespace backend\controllers;

use Yii;
use backend\models\GoodsCategory;
use backend\models\search\GoodsCategorySearch;
use backend\components\NotFoundHttpException;

use yii\helpers\ArrayHelper;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class GoodsCategoryController extends BaseController
{

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new GoodsCategorySearch();
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
        $model = new GoodsCategory();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            $model->parent_id = Yii::$app->request->get('parent_id', 0);
            $data = GoodsCategory::find()
                ->where("is_delete=:is_delete AND parent_id=:parent_id")
                ->addParams([':is_delete'=>0,':parent_id'=>0])
                ->orderBy(['sort' => SORT_ASC,'id'=>SORT_ASC])
                ->select('id,name')->asArray()->all();
            $data = ArrayHelper::map($data,'id','name');
            return $this->render('create', [
                'model' => $model,
                'treeArr' => $data,
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
            $data = GoodsCategory::find()
                ->where("is_delete=:is_delete AND parent_id=:parent_id")
                ->addParams([':is_delete'=>0,':parent_id'=>0])
                ->orderBy(['sort' => SORT_ASC,'id'=>SORT_ASC])
                ->select('id,name')->asArray()->all();
            $data = ArrayHelper::map($data,'id','name');
            return $this->render('update', [
                'model' => $model,
                'treeArr' => $data,
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
     * @return GoodsCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GoodsCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
