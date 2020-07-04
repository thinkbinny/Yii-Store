<?php

namespace backend\controllers;

use Yii;
use backend\models\Menu;
use backend\models\search\MenuSearch;
use backend\components\NotFoundHttpException;
use common\libs\Tree;
use yii\web\Response;
/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends BaseController
{


    public function actionInit(){
        $data = Yii::$app->cache->get('meun');
        if($data===false){
            $model = new Menu();
            $data  = $model->getFindModel();
            Yii::$app->cache->set('meun',$data,120);
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $data;
    }

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new MenuSearch();
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
        $model = new Menu();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            $model->pid = Yii::$app->request->get('pid', 0);
            $arr = Menu::find()->asArray()->all();
            $treeObj = new Tree($arr);
            return $this->render('create', [
                'model' => $model,
                'treeArr' => $treeObj->getTree(),
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
            $arr = Menu::find()->asArray()->all();
            $treeObj = new Tree($arr);
            return $this->render('update', [
                'model' => $model,
                'treeArr' => $treeObj->getTree(),
            ]);
        }
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        $id     = Yii::$app->request->post('id');
        if(empty($id))$this->error('请选择要操作的数据');
        $lists  = 0;
        $data   = (array) $id;
        foreach ($data as $val){
            $model = $this->findModel($val);
            $count = Menu::find()->where("pid=:pid")->addParams([':pid'=>$model->id])->count();
            if($count){
                $this->error('请先删除子菜单');
            }
            $lists  += $model->delete();
        }
        if(!empty($lists)){
            $this->success('操作成功');
        }else{
            $this->error('操作失败');
        }
    }


    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

    }

    /*
     * 修改状态
     */
    /*public function actionStatus(){
        //print_r(self::ta());exit;
    }*/

}
