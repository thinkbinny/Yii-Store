<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of KindEditorAction
 *
 * @author Qinn Pan <Pan ThinkBinny, 274397981@qq.com>
 * @link http://www.51baizhe.com
 * @QQ 274397981
 * @date 2015-3-4
 */
namespace extensions\uploads\controllers;
use extensions\uploads\models\File;
use Yii;
use yii\base\Action;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
class DeleteAction extends Action {
    /**
     *
     * @var Array 保存配置的数组
     */
    public $driver;

    //public $save_path;
    public function init() {
        //close csrf
        Yii::$app->request->enableCsrfValidation = false;
        //默认设置
        parent::init();
    }
    public function run() {
        $isAjax =  Yii::$app->request->isAjax;
        header('Content-Type:application/json; charset=utf-8');
        if($isAjax==1){

            $ids    = (array)Yii::$app->request->post('id');
            if(empty($ids)){
                $ids     = (array)Yii::$app->request->get('id');
            }
            if(empty($ids)){
                $data = ['status'=>false,'message'=>'请选择要操作的数据!'];
                exit(json_encode($data));
            }
            $trl =false;
            foreach ($ids as $val){
                $trl = $this->_delete($val);
            }
            if($trl){
                //删除成功
                $data = ['status'=>true,'message'=>'删除成功!'];
                exit(json_encode($data));
            }else{

                $data = ['status'=>false,'message'=>'删除失败!'];
                exit(json_encode($data));
            }
        }else{
            $id     = Yii::$app->request->get('id');
            $trl = $this->_delete($id);
            if($trl){
                //删除成功
                Yii::$app->session->setFlash('success', '删除成功');

            }else{
                Yii::$app->session->setFlash('error', '删除失败');
            }
            $url = Yii::$app->request->getReferrer();
            return Yii::$app->getResponse()->redirect($url, 302);
        }






    }

    /**
     * 处理动作
     */
    protected function _delete($id){
        $model = new File();
        $trl = $model->DeleteFile($this->driver,$id);
        return $trl;
    }



}

?>
