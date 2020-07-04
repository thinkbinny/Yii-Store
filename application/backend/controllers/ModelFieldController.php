<?php
namespace backend\controllers;

use backend\models\ModelField;
use backend\models\search\ModelFieldSearch;
use common\components\Func;
use backend\components\NotFoundHttpException;
use Yii;


class ModelFieldController extends BaseController {

    /**
     * 模型管理首页
     * @author huajie <banhuajie@163.com>
     */
    public function actionIndex(){
        //$this->layout = '_main_ajax';

        $searchModel = new ModelFieldSearch(); //ModelFieldSearch
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(false); //禁止表头排序
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
        $model = new ModelField();
        $model->scenario = 'create';
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $modelField = new ModelField();
            $modelField->addField($model);
            $model->save();
            $redirect = Yii::$app->session->get('redirect');
            Yii::$app->session->remove('redirect');
            return $this->redirect($redirect);
        }else{
            $redirect = Yii::$app->request->getReferrer();
            Yii::$app->session->set('redirect',$redirect);
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
        $model->scenario = 'update';
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $modelField = new ModelField();
            $modelField->updateField($model);
            $model->save();
            $redirect = Yii::$app->session->get('redirect');//remove
            Yii::$app->session->remove('redirect');
            return $this->redirect($redirect);
        }else {
            $redirect = Yii::$app->request->getReferrer();
            Yii::$app->session->set('redirect',$redirect);
            return $this->render('update', [
                'model' => $model,
            ]);
        }


/*
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }*/
    }

    /**
     * Deletes an existing Admin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    protected function getAttributeLabels($volist){
        $field = '';
        foreach ($volist as $key=> $val){
            $field .=    $key.':'.$val."\r\n";
        }
        return $field;
    }
    protected function getRules($volist){
        $data = '';
        foreach ($volist as $key=> $val){
            $field  =   '';
            foreach ($val as $k=>$v){
                if($k==false){//字段
                    if(is_array($v)){
                        $str    = implode(',',$v);
                        $field .= '{';
                        $field .= $str;
                        $field .= '}';
                    }else{
                        $field .= $v;
                    }
                }elseif(is_int($k)==true){
                    $field .= '|'.$v;
                }else{

                    $field .= '|'.$k.':'.$v;
                }

            }
            $field.="\r\n";
            $data .= $field;
        }
        return $data;
    }

    /*
     *生成模板正则
     */
    public function actionRegular(){
        $model              =   new ModelField();

        if(Yii::$app->request->isPost){
            $attributeLabels    = Yii::$app->request->post('attributeLabels');
            $rules              = Yii::$app->request->post('rules');
            $attributeLabels    = Func::parse_config_attr($attributeLabels);
            $rules              = $model->parse_attr($rules);
            $string             = ['attributeLabels'=>$attributeLabels,'rules'=>$rules];
            $model->CreateFile($string);
            Yii::$app->session->setFlash('success', '创建成功');
            //$this->success('创建成功');
        }//else{
            $data               =   $model->getRegular();
            $attributeLabels    =   $this->getAttributeLabels($data['attributeLabels']);
            $rules              =   $data['rules'];
            $rules = $this->getRules($rules);
            return $this->render('view', [
                'model' => ['attributeLabels'=>$attributeLabels,'rules'=>$rules],
            ]);
        //}
    }


    /**
     * Finds the Admin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ModelField the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ModelField::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /*
     *
     */
    public function actionIndexes(){
        $where      =false;
        $addParams  =false;
        $id     = (int)Yii::$app->request->get('id');
        $val    = (int)Yii::$app->request->get('val');
        if(empty($id))$this->error('缺少ID参数');
        if(!isset($val))$this->error('缺少数据参数');
        $info = $this->findModel($id);
        $model  = new ModelField();
        $field  = 'indexes';

        if($field==false){
            $data = ['status'=>$val];
        }else{
            $data = [$field=>$val];
        }
        if($where==false){
            $where = 'id=:id';
        }
        if($addParams==false){
            $addParams = [':id'=>$id];
        }
        $lists  = $model::updateAll($data,$where,$addParams);
        if($lists){
            $modelField = new ModelField();
            if($val==1){
                $modelField->IndexesField(['model_id'=>$info->model_id,'indexes'=>1,'field'=>$info->field]);
                $this->success('已开启','',['method'=>'open']);
            }else{
                $modelField->IndexesField(['model_id'=>$info->model_id,'indexes'=>0,'field'=>$info->field]);
                $this->success('已禁止','',['method'=>'forbid']);
            }
            exit;
        }else{
            $this->error('操作失败');
        }

    }

}