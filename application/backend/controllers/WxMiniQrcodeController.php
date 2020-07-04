<?php

namespace backend\controllers;

use extensions\weixin\Application;
use Yii;
use backend\models\WxMiniQrcode;
use backend\models\search\WxMiniQrcodeSearch;
use backend\components\NotFoundHttpException;


/**
 * MenuController implements the CRUD actions for Menu model.
 */
class WxMiniQrcodeController extends BaseController
{



    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {


        $app    = new Application();
        $weixin = $app -> driver('mini.qrcode');
       /* $extra  = [
            'width'      => $this->width,
            'auto_color' => $this->auto_color,
            'is_hyaline' => $this->is_hyaline,
        ];
        if(!empty($this->line_color)){
            $extra['line_color'] = $this->line_color;
        }

        $ret =  $weixin -> unLimit(3,'',[]);
        $ret = json_decode($ret,true);
        print_r($ret);exit;
        $base64 = "data:image/jpg;base64," . chunk_split(base64_encode($ret));
        //print_r($base64);
        echo '<img src="'.$base64.'" border="0" />';
        //print_r(chunk_split(base64_encode($ret)));
        exit;
        $ret = json_decode($ret,true);
        */

        $searchModel = new WxMiniQrcodeSearch();
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
        $model = new WxMiniQrcode();

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
    public function actionDelete()
    {
        $id     = Yii::$app->request->post('id');
        if(empty($id))$this->error('请选择要操作的数据');
        $lists  = 0;
        $data   = (array) $id;
        foreach ($data as $val){
            $model = $this->findModel($val);
            //删除图片
            if(file_exists(YII_DIR . $model->pic_url)){
                @unlink(YII_DIR . $model->pic_url);
            }
            $lists  += $model->delete();
        }
        if(!empty($lists)){
            $this->success('操作成功');
        }else{
            $this->error('操作失败');
        }
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WxMiniQrcode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WxMiniQrcode::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
