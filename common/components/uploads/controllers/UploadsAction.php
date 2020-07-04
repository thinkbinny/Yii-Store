<?php
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
use yii\web\UploadedFile;

class UploadsAction extends Action {
    /**
     *
     * @var Array 保存配置的数组
     */
    public $driver;
    public $config;
    public $setting;
    //public $save_path;
    public function init() {
        //close csrf
        Yii::$app->request->enableCsrfValidation = false;
        //默认设置
        parent::init();
    }
    public function run() {
        $this->handAction();
    }

    /**
     * 处理动作
     */
    public function handAction() {
        //获得action 动作
        $action = Yii::$app->request->get('action');
        switch ($action) {
            case 'uploadJson':
                $this->UploadJosnAction();
                break;
            default:
                break;
        }
    }
    /**
     * 文件管理操作
     * @author ${author}
     */
    public function fileManagerJsonAction() {

    }
    public function UploadJosnAction() {
        $model        = new File();
        $group_id     = Yii::$app->request->post('group_id',0);
        $this->setting['group_id'] = $group_id;




        $trl = $model->upload($_FILES,$this->setting,$this->driver); //$this->config
        if(empty($trl)){
            echo Json::encode(['status'=>false,'message'=>$model->error()]);exit;
        }else{
            echo Json::encode(['status'=>true,'pic_url'=>$trl['pic_url'],'name'=>$trl['name']]);exit;
        }
    }



}

?>
