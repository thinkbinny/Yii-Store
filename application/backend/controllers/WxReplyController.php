<?php

namespace backend\controllers;
use backend\models\WxReplyKey;
use Yii;
use backend\models\WxReply;
use backend\models\search\WxReplySearch;
use backend\components\NotFoundHttpException;


/**
 * MenuController implements the CRUD actions for Menu model.
 */
class WxReplyController extends BaseController
{


    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new WxReplySearch();
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
        $model = new WxReply();
        $model->scenario = 'keyword';
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
        $model->scenario = 'keyword';
        $model->keyword = WxReplyKey::getFinds($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,

            ]);
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
    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $model->delete();
        return $this->redirect(['index']);

    }
    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WxReply the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WxReply::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     * 收到信息自动回复
     */
    public function actionAutoreply(){
        $model = new WxReply();
        $info = $model->findOne(['type'=>0]);
        if(!empty($info)){
            $model = $info;
        }
        $model->scenario = 'autoreply';
        if(Yii::$app->request->isPost){
            $model->load(Yii::$app->request->post());
            if($model->msg_type==1){
                if(empty($model->content)){
                    $this->error('回复内容不能为空');
                }
            }else{
                if(empty($model->media_id)){
                    $this->error('素材ID不能为空');
                }
            }
            if($model->isNewRecord){
                $model->name = '自动回复';
                $model->type = 0;
            }
            if($model->validate()){
                $model->save();
                $this->success('提交成功');
            }else{
                $this->error('保存失败');
            }
        }
        return $this->render('_form_auto', [
            'model' => $model,
        ]);
    }

    /**
     * 关注时回复
     */
    public function actionSubscribe(){
        $model = new WxReply();
        $info = $model->findOne(['type'=>2]);
        if(!empty($info)){
            $model = $info;
        }
        $model->scenario = 'subscribe';
        if(Yii::$app->request->isPost){
            $model->load(Yii::$app->request->post());
            if($model->msg_type==1){
                if(empty($model->content)){
                    $this->error('回复内容不能为空');
                }
            }else{
                if(empty($model->media_id)){
                    $this->error('回复内容不能为空');
                }
            }
            if($model->isNewRecord){
                $model->name = '关注时回复';
                $model->type = 2;
            }
            if($model->validate()){
                $model->save();
                $this->success('提交成功');
            }else{
                $this->error('保存失败');
            }
        }
        return $this->render('_form_auto', [
            'model' => $model,
        ]);
    }
}
