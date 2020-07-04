<?php
namespace backend\controllers;

use backend\models\Member;
use backend\models\Tixian;
use backend\models\search\TixianSearch;
use backend\components\NotFoundHttpException;
use Yii;


class TixianController extends BaseController {

    /**
     * 模型管理首页
     * @author Thinkbinny
     */
    public function actionIndex(){

        $searchModel = new TixianSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(false); //禁止表头排序
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
        $this->layout = '_main';
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }
    /**
     * Finds the Admin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tixian the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tixian::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 审核
     * @param $id
     * @return array|string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/3/8 15:50
     */
    public function actionChecker($id,$opt){
        $model = $this->findModel($id);
        if($model->status != 0){
            $this->error('非法操作');
        }
        $status = 0;
        if($opt == 'adopt'){
           $status = 1;
        }elseif($opt == 'fail'){
           $status = -1;
        }else{
            $this->error('非法操作');
        }

        $model->checker_time    = time();
        $model->checker         = Yii::$app->user->identity->realname;//获取当前 登陆账号
        $model->status          = $status;
        $transaction            = Yii::$app->db->beginTransaction();  // 创建事务
        if($model->save()){
            if($opt == 'fail'){
                $data = Member::setProperty($model->uid,'inc',6,$model->money,$model->checker.' 审核提现不通过');
                if($data['status'] == true){
                    $transaction->commit();  // 提交
                    $this->success('操作成功');
                }else{
                    $this->error($data['message']);
                }
            }else{
                $transaction->commit();  // 提交
                $this->success('操作成功');
            }
        }else{
            $transaction->rollBack();  // 回滚
            $this->error('操作失败');
        }
    }

    public function actionAccountant($id){
        $model = $this->findModel($id);
        if($model->status != 1){
            $this->error('非法操作');
        }
        $model->accountant_time    = time();
        $model->accountant         = Yii::$app->user->identity->realname;//获取当前 登陆账号
        $model->status             = 2;
        if($model->save()){
            $this->success('操作成功');
        }else{
            $this->error('操作失败');
        }
    }
}