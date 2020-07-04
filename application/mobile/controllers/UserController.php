<?php
namespace mobile\controllers;
use mobile\models\search\GoodsSearch;
use Yii;

/**
 * Site controller
 */
class UserController extends BaseController
{

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new GoodsSearch();
        $dataProvider = $searchModel->search();//Yii::$app->request->queryParams

        return $this->render('index',[
            'dataProvider' => $dataProvider,
        ]);
    }

}
