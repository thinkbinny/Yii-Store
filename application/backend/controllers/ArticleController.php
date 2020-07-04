<?php
namespace backend\controllers;

use backend\models\Article;
use backend\models\Category;
use backend\models\Model;
use backend\models\search\ArticleSearch;
use common\components\Func;
use backend\models\article\Document;
use backend\components\NotFoundHttpException;
use Yii;

class ArticleController extends BaseController {

    /**
     * 模型属性管理首页
     * @author huajie <banhuajie@163.com>
     */
    public function actionIndex(){
        $category_id = Yii::$app->request->get('category_id');
        $model  = new Category();
        $info   = $model->GetCategoryInfo($category_id);
        if($info['model_id']==3){
            return $this->SinglePage();
        }else{
            return $this->ArticleList();
        }
    }
    /**
     * 列表
     */
    protected function ArticleList(){
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,['in','status',[0,1]]);
        $dataProvider->setSort(false); //禁止表头排序
        $model    =  new Document();
        return $this->render('index', [
            'model'         => $model,
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,

        ]);
    }
    /**
     * 单页
     */
    protected function SinglePage(){


        $category_id = Yii::$app->request->get('category_id');
        $model = new \backend\models\article\SinglePage();
        $model = $model::findOne(['id'=>$category_id]);
        if(empty($model)){
            $model = new \backend\models\article\SinglePage();
        }

        if(Yii::$app->request->isPost){
             if($model->load(Yii::$app->request->post()) && $model->validate())
             {

                 $model->save();
                 Yii::$app->session->setFlash('success', '操作成功');
                 //return $this->redirect(Yii::$app->request->getReferrer());
             }
        }

        $article    =  new \backend\models\Article(3);
        $field      =  $article->ListsField();
        return $this->render('_page', [
            'model'         => $model,
            'field'         => $field,
        ]);
    }
    /**
     * Creates a new Admin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $category_id = 0;
        if(Yii::$app->request->isPost){
            $category_id = (int)Yii::$app->request->get('category_id',0);
        }
        $category_id = (int)Yii::$app->request->get('category_id',$category_id);
        if(empty($category_id)){
            $this->error('请选择发布栏目');
        }
        $category = new Category();
        $info = $category->GetCategoryInfo($category_id);
        if(empty($info)){
            $this->error('栏目不存在或已删除');
        }
        //查出关联表
        $correlation    = new Model();
        $volist          = $correlation->CorrelationModel($info['model_id']); // [['model_id'=>1]];//
        $model = array();
        foreach ($volist as $vals){
            $article    =  new \backend\models\Article($vals['model_id']);
            $ListsField =  $article->ListsField();
            $TableName  =  $article->getTableName();
            $className  = 'backend\models\article\\'.$TableName;
            $item = new $className;
            $model[$vals['model_id']] = ['field'=>$ListsField,'tablename'=>$TableName,'item'=>$item];
        }

        //提交保存
        if(Yii::$app->request->isPost){
            $item =array();
            $article_id = 0;
            foreach ($model as $key => $val){
                $item[$key] = $val['item'];
                $item[$key]->load(Yii::$app->request->post());
                $item[$key]->validate();
                foreach ($item[$key]->errors as $trl){
                    $this->error($trl[0]);
                }
                if(!empty($article_id)){
                    $item[$key]->id  = $article_id;
                }
                $item[$key]->save();
                $article_id = Yii::$app->db->getLastInsertID();
            }
            return $this->redirect(['index','category_id'=>$category_id]);
        }


        return $this->render('create', [
            'model'            => $model,
        ]);
    }
    /**
     * Updates an existing Admin model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $id         = Yii::$app->request->get('id',0);
        $category_id = 0;
        if(Yii::$app->request->isPost){
            $category_id = (int)Yii::$app->request->get('category_id',0);
        }
        $category_id = (int)Yii::$app->request->get('category_id',$category_id);
        if(empty($category_id)){
            $this->error('请选择发布栏目');
        }
        $category = new Category();
        $info = $category->GetCategoryInfo($category_id);
        if(empty($info)){
            $this->error('栏目不存在或已删除');
        }
        //查出关联表
        $correlation    = new Model();
        $volist          = $correlation->CorrelationModel($info['model_id']); // [['model_id'=>1]];//
        $model = array();
        foreach ($volist as $vals){
            $article    =  new \backend\models\Article($vals['model_id']);
            $ListsField =  $article->ListsField();
            $TableName  =  $article->getTableName();
            $className  = 'backend\models\article\\'.$TableName;
            $item = new $className;
            $item = $item->find()->where('id=:id')->addParams([':id'=>$id])->one();
            $model[$vals['model_id']] = ['field'=>$ListsField,'tablename'=>$TableName,'item'=>$item];
        }

        //提交保存
        if(Yii::$app->request->isPost){
            $item =array();
            $article_id = 0;
            foreach ($model as $key => $val){
                $item[$key] = $val['item'];
                $item[$key]->load(Yii::$app->request->post());
                $item[$key]->validate();
                foreach ($item[$key]->errors as $trl){
                    $this->error($trl[0]);
                }
                if(!empty($article_id)){
                    $item[$key]->id  = $article_id;
                }
                $item[$key]->save();
                $article_id = Yii::$app->db->getLastInsertID();
            }
            return $this->redirect(['index','category_id'=>$category_id]);
        }


        return $this->render('update', [
            'model'          => $model,

        ]);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Document the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {

        if (($model = Document::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     * Deletes an existing Admin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        return $this->ModelAll(-1);
    }



    /**
     * Finds the Admin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function ModelAll($status=-1)
    {
        $ids           = (array) Yii::$app->request->post('id');
        if(empty($ids)){
            $ids       = (array) Yii::$app->request->get('id');
        }


        if ( empty($ids) ) {
            $this->error('请选择要操作的数据!');
        }
        $time       = time();
        $model      = Document::updateAll(['status'=>$status,'updated_at'=>$time],['in','id',$ids]);
        $this->success('操作成功');
        /*if(Yii::$app->request->post('id')){
            $this->success('操作成功');
        }else{
            return $this->redirect(Yii::$app->request->getReferrer());
        }*/
    }

    /**
     * 内容待审核中
     * @return string
     */
    public function actionAuditing(){
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,['in','status',[2]]);
        //$dataProvider->setSort(false); //禁止表头排序
        $model    =  new Document();
        return $this->render('auditing', [
            'model'         => $model,
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,

        ]);
    }

    /**
     * 审核通过
     * @return string
     */
    public function actionRestore(){
        return $this->ModelAll(1);
    }

    /**
     * 内容回收站
     * @return string
     */
    public function actionRecycle(){
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,['in','status',[-1]]);
        $dataProvider->setSort(false); //禁止表头排序
        $model    =  new Document();
        $model->getCategoryName(1);
        return $this->render('recycle', [
            'model'         => $model,
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,

        ]);
    }
    /**
     * 还原 permit
     * @return string
     */
    public function actionPermit(){
        return $this->ModelAll(1);
    }
    /**
     * 清空删除（删除后无法还原）
     * @return string
     */
    public function actionClear(){
        $ids         = Yii::$app->request->post('id');
        if(empty($ids)){
            $ids       = Yii::$app->request->get('id');
        }
        if(isset($ids))
            $ids =  is_array($ids) ? $ids : [$ids];
        if ( empty($ids) ) {
            $this->error('请选择要操作的数据!');
        }
        $model = new Document();
        $model_list = $model->find()
            ->where(['in','id',$ids])
            ->select('distinct(model_id)')
            ->asArray()
            ->all();
        foreach ($model_list as $value){
            $model_id   = $value['model_id'];
            $Article    = new Article($model_id);
            $TableName  = $Article->getTableName();//查出数据表 exit;
            //查出在此表 的选择数据
            $list = $model->find()
                ->where('model_id=:model_id')
                ->addParams([':model_id'=>$model_id])
                ->andFilterWhere(['in','id',$ids])
                ->select('id')
                ->asArray()
                ->all();
            $list_ids = Func::array_column($list,'id');

            $className  = 'backend\models\article\\'.$TableName;
            $item = new $className;
            //执行删除
            $model::deleteAll(['in','id',$list_ids]);
            $item::deleteAll(['in','id',$list_ids]);
            //执行删除
            $this->success('操作成功');
            /*if(Yii::$app->request->post('id')){
                $this->success('操作成功');
            }else{
                return $this->redirect(Yii::$app->request->getReferrer());
            }*/
        }
    }




}