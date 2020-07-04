<?php
namespace backend\controllers;

use backend\models\SystemLog;
use common\widgets\PHPGangsta\GoogleAuthenticator;
use common\widgets\phpqrcode\Phpqrcode;
use Yii;
use backend\models\Admin;
use backend\models\search\AdminSearch;
use backend\components\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\rbac\Item;
use backend\models\search\AuthItemSearch;

/**
 * AdminController implements the CRUD actions for Admin model.
 */
class AdminController extends BaseController
{

    public $type = Item::TYPE_ROLE;

    /**
     * Lists all Admin models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminSearch();//SystemLog::log('');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(false); //禁止表头排序
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Admin model.
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
     * Creates a new Admin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Admin();
        $model->scenario = 'create';
        $model->status = Admin::STATUS_ACTIVE;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Admin model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
        if ($model->load(Yii::$app->request->post())) {
            if($id==1 && $model->status==0){
                Yii::$app->session->setFlash('error', '超级管理员不能禁止');
            }elseif($model->save()){
                return $this->redirect(['index']);
            }
            return $this->render('update', [
                'model' => $model,
            ]);

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Admin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if($id==1){
            $this->error('禁止删除超级管理员');
            /*Yii::$app->session->setFlash('error', '禁止删除管理员');
            return $this->redirect(['index']);*/
        }
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    public function actionStatus(){
        $id     = Yii::$app->request->post('id');
        if($id==1){
            $this->error('无法禁止超级管理员');
        }
        parent::actionStatus();
    }
    /**
     * 受权配置
     * @param $id
     * @return array|string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2019/9/2 21:04
     */
    public function actionAuth($id) {
        $this->layout = '_main_ajax';
        $authManager = Yii::$app->authManager;
        //$adminModel = $this->findModel($id);
        if(Yii::$app->request->isPost) {
            $roleName = (array)Yii::$app->request->post('roleName');
            if(empty($roleName)){
                $this->error('请选择角色');exit;
            }
            //删除用户所在的用户组
            $authManager->revokeAll($id);
            //添加用户组
            foreach ($roleName as $name){
                $name   = $authManager->getRole($name);
                $authManager->assign($name, $id);
            }
            $this->success('受权设置成功');
        }
        $searchModel = new AuthItemSearch();
        $searchModel->type = $this->type;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataAll = array();
        foreach ($dataProvider->getModels() as $val){
            $dataAll[$val->name] = $val->description;

        }

        //获取当前用户的所有用户组
        $adminGroups = array_keys($authManager->getAssignments($id));
        //var_dump($dataProvider);exit();
        return $this->render('auth', [
            //'searchModel' => $searchModel,
            //'dataProvider' => $dataProvider,
            'dataAll' =>$dataAll,
            'adminGroups' => $adminGroups,
        ]);
    }

    /**
     * Finds the Admin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Admin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @return string|\yii\web\Response修改个人信息
     */
    public function actionEditinfo(){
        $id = Yii::$app->user->identity->id;
        $model = $this->findModel($id);

        $model->scenario = 'editinfo';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->success('修改成功');

        } else {

            return $this->render('editinfo', [
                'model' => $model,
            ]);
        }
    }
    /*
     * 修改密码
     */
    public function actionResetpwd(){
        $id = Yii::$app->user->identity->id;
        $model = $this->findModel($id);
        $model->scenario = 'resetpwd';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $this->success('修改密码成功');

        } else { //Yii::$app->session->setFlash('success', '修改密码成功');
            return $this->render('resetpwd', [
                'model' => $model,
            ]);
        }
    }
    //生成google认证
    public function actionGoogleMake(){
        $uid    = Yii::$app->request->get('uid');
        $google = new GoogleAuthenticator();
        $title  = Yii::$app->request->hostInfo;
        $title  = trim($title,'http://');
        $title  = trim($title,'https://');

        $secret     = $google->createSecret();
        $GoogleAuthenticator = new \backend\models\GoogleAuthenticator();
        $info       = $GoogleAuthenticator->googleCode($uid,$secret);
        $username   = $info['username'];
        $secret     = $info['secretkey'];
        $qrCodeUrl  = $google->getQRCodeGoogleUrl($username, $secret,$title);
        $Phpqrcode  = new Phpqrcode();
        $Phpqrcode->make($qrCodeUrl);exit;
    }
    //开启认证
    public function actionGoogleAuthenticator(){
        $uid    = Yii::$app->request->post('uid');
        $type   = Yii::$app->request->post('type');
        $model  = \backend\models\GoogleAuthenticator::findOne(['id'=>$uid]);
        if($type=='open'){
            $model->status = 1;
            if($model->save()){
                $this->success('开启谷歌认证成功!');
            }else{
                $this->error('开启谷歌认证失败!');
            }
        }elseif($type=='close'){
            $model->status = 0;
            if($model->save()){
                $this->success('关闭谷歌认证成功!');
            }else{
                $this->error('关闭谷歌认证失败!');
            }
        }else{
            $google             = new GoogleAuthenticator();
            $secret             = $google->createSecret();
            $model->secretkey   = $secret;
            $model->status      = 0;
            if($model->save()){
                $this->success('已重置，请重新认证!');
            }else{
                $this->error('重置失败!');
            }
        }
    }
}
