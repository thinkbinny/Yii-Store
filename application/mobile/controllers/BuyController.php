<?php
namespace mobile\controllers;
use common\components\Func;
use mobile\models\Goods;
use mobile\models\GoodsAttrValue;
use mobile\models\GoodsSku;
use mobile\models\Order;
use mobile\models\search\GoodsSearch;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * Site controller
 */
class BuyController extends BaseController
{

    protected static function getGoodsInfo($skuData){


        $skuId = ArrayHelper::map($skuData,'','skuId');
        //'LEFT JOIN','tbl_user','tbl_user.admin_id=tbl_admin.id'
        $volist = GoodsSku::find()
            ->alias('sku')
            ->andWhere(['in','sku.id',$skuId])
            ->leftJoin(Goods::tableName().' as g','sku.goods_id = g.id')
            ->select('sku.id as skuId,sku.goods_id,sku.sku_attr_id,sku.price,sku.stock,sku.goods_weight,g.title,g.image_id,g.prom_id,g.prom_type,g.is_fenxiao,g.is_vip,g.commission,g.is_points_discount,g.is_free_shipping,g.delivery_id')
            ->asArray()
            ->all();
        $data = array(); //print_r($skuData);exit;
        foreach ($volist as $value){
            $attribute = array();
            if(!empty($value['sku_attr_id'])){
                $attr = explode('_',$value['sku_attr_id']);
                foreach ($attr as $attr_id){
                    $attribute[] = GoodsAttrValue::findModelData($attr_id);
                }
            }
            $value['image_url']     = Func::getImageUrl($value['image_id']);
            unset($value['image_id']);
            $value['quantity']      = $skuData[$value['skuId']]['quantity'];
            $value['unionParams']   = $skuData[$value['skuId']]['unionParams'];
            $value['attribute']     = $attribute;
            $data[] = $value;
        }
        return $data;
    }
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
       $params = Yii::$app->request->get('params');
       if(empty($params)){
           $this->error('系统异常');
       }
        $params = Json::decode($params,true);
        $data   = ArrayHelper::index($params['data'],'skuId',null);

       $model   = self::getGoodsInfo($data);

       return $this->render('index',[
            'model' => $model
       ]);
    }


}
