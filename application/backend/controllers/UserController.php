<?php
namespace backend\controllers;

use backend\models\Member;
use backend\models\search\UserSearch;
use common\models\User;
use backend\components\NotFoundHttpException;
use Yii;


class UserController extends BaseController {

    /**
     * 模型管理首页
     * @author Thinkbinny
     */
    public function actionIndex(){

        $searchModel = new UserSearch();
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
        $model = new Member();
        //$model->scenario = 'create';
        if (Yii::$app->request->isPost ) {
            $Member = Yii::$app->request->post('Member');
            if(!empty($Member['email']) && !empty($Member['mobile'])){
                $model->scenario = 'createORemailORmobile';
            }elseif(!empty($Member['email'])){
                $model->scenario = 'createORemail';
            }elseif(!empty($Member['mobile'])){
                $model->scenario = 'createORmobile';
            }else{
                $model->scenario = 'create';
            }
            $model->load(Yii::$app->request->post());
            if($model->CreateUser()){
                return $this->redirect(['index']);
            }


            //创建数据表 && $model->save()
            return $this->render('create', [
                'model' => $model,
            ]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }




    /*
     * 更新状态
     */
    public function actionStatus(){
        $model = new Member();
        $id     = Yii::$app->request->post('id');
        $value  = Yii::$app->request->post('value');
        $name   = Yii::$app->request->post('name');
        if(empty($id))$this->error('请选择要操作的数据');
        if(!isset($name))$this->error('缺少字段参数');
        if(!isset($value))$this->error('缺少数据参数');
        $data   = (array) $id;
        if($value == 1){
            $value = Member::STATUS_ACTIVE;
        }
        $result = $model->UpdateSwitch($data,$value);
        if(!empty($result)){
            $this->success('操作成功');
        }else{
            $this->error('操作失败');
        }
    }


    /**
     * Displays a single Menu model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->layout = '_main';
        $model = $this->findModel($id);
        $user = User::find()
            ->where('id=:id')
            ->addParams([':id'=>$id])
            ->select('id,username,email,mobile')
            ->one();
        return $this->render('view', [
            'model' => $model,
            'user'  =>$user,
        ]);
    }


    /**
     * Finds the Admin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Member the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Member::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/5 22:01
     */
    public function actionRecharge($id){
        $this->layout = '_main_ajax';
        $model = $this->findModel($id);
        $model->scenario = 'recharge';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['index']);
        } else {
            return $this->render('recharge', [
                'model' => $model,

            ]);
        }
    }
    /**
     * 更新会员等级
     * @param $id
     * @return $this|array|string|\yii\web\Response
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/5 22:01
     */
    public function actionGrade($id){
        $this->layout = '_main_ajax';
        $model = $this->findModel($id);
        $model->scenario = 'grade';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('grade', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 重置密码
     * @return array|string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/6 9:52
     */
    public function actionResetpassword(){
        $this->layout = '_main_ajax';
        $model = new Member();
        $model->scenario = 'resetPassword';
        if (Yii::$app->request->isPost ) {

        }else{
            return $this->render('reset-password', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 选择用户
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/17 13:41
     */
    public function actionSelect(){
        $this->layout = '_main';
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(false); //禁止表头排序
        return $this->render('_select', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }
}