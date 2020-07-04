<?php
namespace mobile\controllers;
use common\components\Func;
use mobile\models\Goods;
use mobile\models\GoodsSku;
use mobile\models\Order;
use mobile\models\search\GoodsSearch;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * Site controller
 */
class GoodsController extends BaseController
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

    /**
     * 详细页面
     * @return string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/7 22:01
     */
    public function actionView(){
        $id = 1;
        $model = Goods::find()
            ->where("is_delete=:is_delete and id=:id")
            ->addParams([':is_delete'=>0,':id'=>$id])
            ->with('content')
            ->with('image')
            ->asArray()
            ->one();
        $sku = GoodsSku::findViewModel($model['id']);

        return $this->render('view',[
            'model' => $model,
            'sku'   => Json::encode($sku),
        ]);
    }


    /**
     * 购买
     * @return string
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/2/26 18:02
     */
    public function actionBuy(){
        if(Yii::$app->request->isPost){
            $quantity   = Yii::$app->request->post('quantity',0);
            $sku_id     = Yii::$app->request->post('sku_id',0);
            if(empty($quantity)){
                $this->error('请填写购买数量');
            }elseif(empty($sku_id)){
                $this->error('请选择购买规格');
            }

            /*'marketType'    =>'',//交易类型（如）
                'sourceMarket'  =>'',//来源 如 手机 小程序 电脑
                'deliveryType'  =>'1',//配送方式
                'deliveryData'  =>[
                'addressId'     =>'',//收货地址ID
                'name'          =>'',
                'phone'         =>''
            ],*/
            $data = [
                'orderFrom'=>'detail',//'cart',
                'data' => [
                    [
                        'skuId'         =>$sku_id,
                        'quantity'      =>$quantity,
                        'unionParams'   =>'1',//分销参数
                    ]
                ]
            ];
            $params = Json::encode($data);
            //print_r($params);exit;
            $url = Url::to(['buy/index','params'=>$params]);

            $this->success('',$url);
        }else{
            $this->error('非法操作');
        }

    }
    /**预览订单**/
    public function actionOrder(){

        //$data = Yii::$app->session->get('confirm_order');
        //print_r($data);
        //Yii::$app->response->cookies->remove('confirm_order');
        //Yii::$app->response->cookies->add($cookie);
        //Yii::$app->response->send();//print_r($cookie);exit;

        //$cookie = Yii::$app->request->cookies->get('confirm_order');
        //print_r($cookie);//exit;
        //放入cookies
        //Yii::$app->response->cookies

        /*     $amount  =  Yii::$app->request->post('amount');
             $goods_id= Yii::$app->request->post('goods_id');
             $sku_id  = Yii::$app->request->post('sku_id');*/
        /*$model = new self();
        $model -> amount         = $amount;
        $model -> goods_id       = $goods_id;
        $model -> sku_id         = $sku_id;
        $model -> uid            = 1;
        $model -> fengxiao_uid   = 0;*/

        $attr = new Order();
        /*
                $url = 'https://h5.mogu.com/buy/index.html?params=%7B%22ptpCnt%22%3A%2232.GrPvGb.0.0.nsA3gYFT%22%2C%22shops%22%3A%5B%7B%22skus%22%3A%5B%7B%22stockId%22%3A%2211cn3pti%22%2C%22number%22%3A1%2C%22ptp%22%3A%2232.rPCjb.undefined.2.VuQ20adv%22%2C%22liveParams%22%3A%22%22%2C%22unionParams%22%3A%22%22%2C%22fashionParams%22%3A%22%22%7D%5D%7D%5D%2C%22orderFrom%22%3A%22detail%22%2C%22sharerId%22%3A%22%22%2C%22marketType%22%3A%22market_mogujie%22%2C%22sourceMarket%22%3A%22%22%2C%22platFormType%22%3A%22%22%2C%22addressId%22%3A%22%22%2C%22modouUse%22%3A0%7D&ptp=32.GrPvGb.0.0.nsA3gYFT';
                //$url = '<!--https://h5.mogu.com/buy/index.html?params=%7B%22orderFrom%22%3A%22cart%22%2C%22shops%22%3A%5B%7B%22skus%22%3A%5B%7B%22stockId%22%3A%22116od2y8%22%2C%22number%22%3A1%2C%22ptp%22%3A%220.u0u8SkEf._items.1.4uBDswFX%22%2C%22ptpCnt%22%3A%220.poYALe3u.0.0.D2iK4JyX%22%7D%5D%7D%2C%7B%22skus%22%3A%5B%7B%22stockId%22%3A%22118mavls%22%2C%22number%22%3A1%2C%22ptp%22%3A%220.u0u8SkEf._items.2.4uBDswFX%22%2C%22ptpCnt%22%3A%220.poYALe3u.0.0.BmhKZ5Dh%22%7D%5D%7D%5D%7D&ptp=32.b941P.0.0.RS1QIclr-->';
                $a = parse_url($url);
                $query = $a['query'];
                $query = urldecode($query);
                print_r($query);
                exit;*/
         $attr->OrderSubmit();
       /* return $this->render('order',[

        ]);*/
    }
}
