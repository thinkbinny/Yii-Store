<?php

namespace backend\controllers;


use backend\models\Config;
use Yii;

use backend\models\search\IntegralSearch as search;
use yii\helpers\Json;


/**
 * MenuController implements the CRUD actions for Menu model.
 */
class IntegralController extends BaseController
{

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSetting(){
        $id = 	5;
        $model           = Config::findOne($id);
        $model->scenario = 'setup';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['setting','id'=>$id]);
        } else {
            $model->data = Json::decode($model->data,true);
            return $this->render('setting', [
                'model' => $model,
            ]);
        }

    }

}
