<?php
namespace mobile\controllers;
use mobile\models\Cart;
use mobile\models\Goods;
use mobile\models\GoodsSku;
use mobile\models\search\GoodsSearch;
use Yii;
use yii\helpers\Json;

/**
 * Site controller
 */
class CartController extends BaseController
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

   public function actionJoin(){
       $quantity   =  Yii::$app->request->post('quantity');
       $sku_id   = Yii::$app->request->post('sku_id',0);
       $model = new Cart();
       $model -> quantity         = $quantity;
       $model -> sku_id         = $sku_id;
       $model -> uid            = 1;
       $model -> fengxiao_uid   = 0;
       if($model->setCart()){
           $this->success('已成功入购物车');
       }else{
           $error = $model->getFirstErrors();
           $error = current($error);
           $this->error($error);
       }

   }
}
