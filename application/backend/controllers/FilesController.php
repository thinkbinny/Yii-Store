<?php
namespace backend\controllers;

use backend\models\UploadFile;
use backend\models\search\UploadFileSearch;
use backend\components\NotFoundHttpException;
use backend\models\UploadGroup;
use backend\components\AccessControl;
use yii\filters\VerbFilter;
use Yii;


class FilesController extends BaseController {
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['browsefile', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'logout' => ['post'],
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    public function actions()
    {
        $config = Yii::$app->params['uploads'];
        $local  = $config['local'];

        return [
            'Uploads' => [
                'class'             => 'extensions\uploads\controllers\UploadsAction',
                'driver'            => $config['storage'],
                'setting'   =>[
                    'uid'           =>  0,//上传用户UID 0后台上传
                    'mimes'         =>  array(), //允许上传的文件MiMe类型
                    'maxSize'       =>  $local['size'], //上传的文件大小限制 (0-不做限制)
                    'exts'          =>  $local['exts'], //允许上传的文件后缀
                    'autoSub'       =>  true, //自动子目录保存文件
                    'subName'       =>  '', //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
                    'rootPath'      =>  $local['uploadfile'], //保存根路径
                    'savePath'      =>  '/library/'.date('Ymd').'/', //保存路径
                    'saveName'      =>  array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
                    'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
                    'replace'       =>  false, //存在同名是否覆盖
                    'hash'          =>  true, //是否生成hash编码
                    'callback'      =>  false, //检测文件是否存在回调，如果存在返回文件信息数组
                    'driver'        =>  '', // 文件上传驱动
                    'driverConfig'  =>  array(
                        'watermark_enable'      => $local['watermark_enable'],
                        'watermark_pos'         => $local['watermark_pos'],   //1~9 0代码随机
                        'watermark_text'        => $local['watermark_text'],  //水印内容
                        'watermark_text_path'   => $local['watermark_text_path'], //文字水印字体文件
                        'watermark_images_path' => $local['watermark_images_path'], //图片水印文件地址

                        'domain'=> '',//域名
                    ), // 上传驱动配置
                ],
                'config'=>[

                ],
            ],
            'delete' => [
                'class'     => 'extensions\uploads\controllers\DeleteAction',
                'driver'    => $config['storage'],//删除驱动

            ]
        ];
    }
    /**
     * 模型管理首页
     * @author Thinkbinny
     */
    public function actionIndex(){

        $searchModel = new UploadFileSearch();
        $searchModel->is_delete = 0;
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

        $model = new UploadFile();

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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,

            ]);
        }
    }
    /**
     * Finds the Admin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UploadFile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UploadFile::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 预览文件
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/10 12:34
     */
    public function actionBrowsefile(){

        $group_id       = Yii::$app->request->get('group_id',-1);
        $this->layout   = '_main';
        $searchModel    = new UploadFileSearch();
        //文件分类
        $filesGroup = $searchModel->getGroupId();
        $searchModel->is_delete = 0;
        if($group_id>=0){
            $searchModel->group_id  = $group_id;
        }
        $dataProvider   = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(false); //禁止表头排序
        return $this->render('browsefile', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filesGroup'=>$filesGroup,
            'group_id'=>$group_id,
        ]);
    }

    /**
     * 删除图片
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/14 11:24
     */
    public function actionDel(){
        $id     = Yii::$app->request->post('id');
        if(empty($id))$this->error('请选择要操作的数据');
        $lists  = 0;
        if(!is_array($id)){
            if(strpos($id,',') !== false){
                $id = explode(',',$id);
            }
        }
        $data   = (array) $id;
        foreach ($data as $val){
            $model = $this->findModel($val);
            $model->is_delete = 1;
            $lists  += $model->save();
        }
        if(!empty($lists)){
            $this->success('操作成功');
        }else{
            $this->error('操作失败');
        }
    }

    /**
     * 移动栏目
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/14 20:09
     */
    public function actionMove(){
        $id         = Yii::$app->request->post('id');
        $group_id   = Yii::$app->request->post('group_id');
        if(empty($id))$this->error('请选择要操作的数据');
        if(empty($group_id))$this->error('请选择分组');
        $lists  = 0;
        if(strpos($id,',') !== false){
            $id = explode(',',$id);
        }
        $data   = (array) $id;
        foreach ($data as $val){
            $model = $this->findModel($val);
            $model->group_id = $group_id;
            $lists  += $model->save();
        }
        if(!empty($lists)){
            $this->success('操作成功');
        }else{
            $this->error('操作失败');
        }
    }

    /**
     * 回收站
     * @return array|string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/15 23:00
     */
    public function actionRecycle(){
        $searchModel = new UploadFileSearch();
        $searchModel->is_delete = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(false); //禁止表头排序
        return $this->render('recycle', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 还原
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/1/15 23:02
     */
    public function actionRestore(){

        if(Yii::$app->request->isGet){
            $id     = Yii::$app->request->get('id');
            $model = $this->findModel($id);
            $model->is_delete=0;
            if($model->save()){
                $this->success('操作成功');
            }else{
                $this->error('操作失败');
            }
        }else{
            return parent::actionStatus();
        }
    }


}