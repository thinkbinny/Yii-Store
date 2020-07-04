<?php

namespace backend\controllers;

use Yii;
use backend\models\Slides;
use backend\models\search\SlidesSearch;
use backend\components\NotFoundHttpException;


/**
 * MenuController implements the CRUD actions for Menu model.
 */
class SlidesController extends BaseController
{
    public function actions()
    {
        return [
            'Uploads' => [
                'class' => 'common\plugin\uploads\controllers\UploadsAction',
                'driver'=>'Local',
                'setting'=>[
                    'uid'           =>  1,//上传的UID 默认0 为系统上传图片
                    'mimes'         =>  array(), //允许上传的文件MiMe类型
                    'maxSize'       =>  0, //上传的文件大小限制 (0-不做限制)
                    'exts'          =>  array(), //允许上传的文件后缀
                    'autoSub'       =>  true, //自动子目录保存文件
                    'subName'       =>  'slides', //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
                    'rootPath'      =>  './uploads/', //保存根路径
                    'savePath'      =>  '', //保存路径
                    'saveName'      =>  array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
                    'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
                    'replace'       =>  false, //存在同名是否覆盖
                    'hash'          =>  true, //是否生成hash编码
                    'callback'      =>  false, //检测文件是否存在回调，如果存在返回文件信息数组
                    'driver'        =>  '', // 文件上传驱动
                    'driverConfig'  =>  array(), // 上传驱动配置
                ],
                'config'=>[

                ],
            ]
        ];
    }
    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->request->isPost) {
            $sorts = Yii::$app->request->post('sort');
            if (!empty($sorts)) {
                foreach ($sorts as $id => $v) {
                    $model = Slides::findOne($id);
                    $model->sort = $v;
                    $model->save();
                }
                Yii::$app->session->setFlash('success', '操作成功');
            }
        }
        $searchModel = new SlidesSearch();
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
        $model = new Slides();

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
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    /**
     * Changestatus an existing Model model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionChangestatus()
    {

        if(Yii::$app->request->isPost){
            $val = Yii::$app->request->get('val');
            $ids = Yii::$app->request->post('id');
            if(isset($ids))
                $ids =  is_array($ids) ? $ids : [$ids];
            if ( empty($ids) ) {
                $this->error('请选择要操作的数据!');
            }
            $lists = Slides::updateAll(['status'=>$val],['in','id',$ids]);

            if($lists){
                if($val==1){
                    $this->success('已开启','',['method'=>'open']);
                }else{
                    $this->success('已禁止','',['method'=>'forbid']);
                }
                exit;
            }else{
                $this->error('操作失败');
            }
        }else{
            $this->UpdateSwitch(new Slides(),'status');
        }
    }
    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Slides the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Slides::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
