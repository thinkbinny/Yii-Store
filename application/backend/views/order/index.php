<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = Yii::$app->controller->id;
$action     = Yii::$app->controller->action->id;//$action
$this->params['thisUrl'] = $controller.'/'.$action;
$breadcrumbs = [
  'index'           =>  '全部订单列表',
  'delivery-list'   =>  '待发货订单列表',
  'receipt-list'    =>  '待收货订单列表',
  'pay-list'        =>  '待付款订单列表',
  'complete-list'   =>  '已完成订单列表',
  'cancel-list'     =>  '已取消订单列表',
];
$this->title = $breadcrumbs[$action];

//$this->title = '全部订单列表';
$this->params['breadcrumbs'][] = ['label' => '订单管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;

$css = <<<CSS
.order-table p{padding:2px 0;overflow: hidden;max-height:22px;text-overflow:ellipsis;}
.order-table thead tr{background: #fff;}
.order-table thead th{font-weight: 700}
.order-table tr th,.order-table tr td{text-align: center;}
.order-table tr td{padding:7px 10px;vertical-align: middle !important;}
.order-table tr:hover{background-color: #fff !important;}
.order-table tr .order_row{border-left-width: 0;border-right-width: 0;}
.order-table tr .order_sn{text-align: left;}
.order-table tr .order_sn span{display: inline-block;}
.order-table tr .order_sn span:last-child{float: right ;color:#8b8b8b;}
.order-table .goods-detail > div{float: left;}
.order-table .goods-detail .goods-image{margin-right: 6px;}
.order-table .goods-detail .goods-image .pic_url {width: 72px;height: 72px;background: no-repeat center center / 100%; }
.order-table .goods-detail .goods-info{width: 240px;height: 72px;}
.order-table .goods-detail .goods-info p { display: block;white-space: normal;margin: 0 0 3px 0;padding: 0 5px;text-align: left; }
.order-table .goods-detail .goods-info p.goods-title {max-height: 40px;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical; -webkit-line-clamp: 2; text-align: left !important;white-space: normal; }
.order-table .goods-detail .goods-info .goods-attr {border: none;font-size: 12px;color: #7b7b7b }
.order-table .link-text{color:#7b7b7b}
CSS;
$this->registerCss($css);

?>

<div class="layuimini-container">
    <div class="layuimini-main">
    <?=$this->render('_tab_menu');?>
    <?=$this->render('_search',['model'=>$searchModel]);?>

    <?
    echo GridView::widget([
        'options'=>['class'=>'layui-form'],
        'tableOptions'=>['class'=>'layui-table order-table'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items} {pager}',//{summary}
        'beforeRow'=>function($model,$key, $index){
            $order_sn   = "<span>订单号：". $model->order_sn ."</span>";
            $date       = "<span>创建时间：". date('Y-m-d H:i:s',$model->created_at) ."</span>";
            $html  = '<tr ><td class="order_row" colspan="8"></td></tr>';
            $html .='<tr><td class="order_sn" colspan="8" >'. $order_sn. $date. '</td></tr>';
            return $html;
        },

        'afterRow'=>function($model,$key, $index,$grid){
            //<tr><td colspan='2'>我是基数</td></tr>
            if(count($model->goods) >1){
                $html = '';
                foreach ($model->goods as $key => $goods){
                    if($key>0){
                    $html = '<tr>';
                    $pic_url = $model -> getImageUrl($goods['image_id']);
                    $img  = Html::tag('div','',['class'=>'pic_url','style'=>"background-image:url('{$pic_url}')"]);
                    $attr = $goods['sku_attr_name'];
                    $info = Html::tag('p',$goods['title'],['class'=>'goods-title']) . Html::tag('p',$attr,['class'=>'goods-attr']);
                    $titleText = Html::tag('div',$img,['class'=>'goods-image']);
                    $titleText .= Html::tag('div',$info,['class'=>'goods-info']);

                    $priceText  = Html::tag('p','￥'.$goods['price']);
                    $priceText  .= Html::tag('p','×'.$goods['quantity']);

                    $html .= '<td class="goods-detail">'. $titleText .'</td><td >'.$priceText.'</td>';
                    $html .= '</tr>';
                    }
                }
                return $html;
            }
        },
        'columns' => [
            [
                'header'    => '商品信息',
                'format'    => 'raw',
                'options'   => ['width'=>350],
                'contentOptions'   => ['class'=>'goods-detail'],
                'value'     => function($model){
                    $goods = $model->goods[0];
                    $pic_url = $model -> getImageUrl($goods['image_id']);
                    $img  = Html::tag('div','',['class'=>'pic_url','style'=>"background-image:url('{$pic_url}')"]);
                    $attr = $goods['sku_attr_name'];
                    $info = Html::tag('p',$goods['title'],['class'=>'goods-title']) . Html::tag('p',$attr,['class'=>'goods-attr']);
                    $html = Html::tag('div',$img,['class'=>'goods-image']);
                    $html .= Html::tag('div',$info,['class'=>'goods-info']);
                    return $html;
                }

            ],

            [
                'header'=>'单价/数量 ',
                'format' => 'raw',
                'options'=>['width'=>110,],
                'value'=>function($model){
                    $goods = $model->goods[0];
                    $html  = Html::tag('p','￥'.$goods['price']);
                    $html  .= Html::tag('p','×'.$goods['quantity']);
                    return $html;
                }
            ],
            [
                'header'=>'实付款',
                'format' => 'raw',
                'options'=>['width'=>140,],
                "contentOptions" => function($model){
                    $row = count($model->goods);
                    return ['rowspan' => $row];
                },
                'value'=>function($model){
                    $html  = Html::tag('p','￥'.$model->pay_price);
                    $html  .= Html::tag('p',"(含运费：￥{$model->shipping_price})",['class'=>'link-text']);
                    return $html;
                },
            ],
            [
                'header'=>'买家',
                'format' => 'raw',
                'options'=>['width'=>150,],
                "contentOptions" => function($model){
                    $row = count($model->goods);
                    return ['rowspan' => $row];
                },
                "value"=>function($model){
                    $html  = Html::tag('p',$model->getNicknameText());
                    $html  .= Html::tag('p',"(用户id：{$model->uid})",['class'=>'link-text']);
                    return $html;
                }
            ],
            [
                'header'=>'支付方式',
                'format' => 'raw',
                'options'=>['width'=>90,],
                "contentOptions" => function($model){
                    $row = count($model->goods);
                    return ['rowspan' => $row];
                },
                "value"=>function($model){
                    return $model->getPayTypeText();
                }
            ],
            [
                'header'=>'配送方式',
                'format' => 'raw',
                'options'=>['width'=>90,],
                "contentOptions" => function($model){
                    $row = count($model->goods);
                    return ['rowspan' => $row];
                },
                "value"=>function($model){
                    return $model->getDeliveryTypeText();
                }
            ],
            [
                'header'=>'交易状态',
                'format' => 'raw',
                'options'=>['width'=>150,],
                "contentOptions" => function($model){
                    $row = count($model->goods);
                    return ['rowspan' => $row];
                },
                "value"=>function($model){
                    $html = '';

                    $html .= Html::tag('p','付款状态：'.$model->getPayStatusText());
                    $html .= Html::tag('p','发货状态：'.$model->getDeliveryStatusText());
                    $html .= Html::tag('p','收货状态：'.$model->getReceiptStatusText());

                    $span  = $model->getOrderStatusText();
                    if(!empty($span)){
                    $html .= Html::tag('p','订单状态：'.$span);
                    }

                    return $html;
                }
            ],


            [
                'options'=>['width'=>110,],
                'class' => 'backend\grid\ActionColumn',
                "contentOptions" => function($model){
                    $row = count($model->goods);
                    return ['rowspan' => $row];
                },
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{delivery} {update} {view}',// {delete}
                'buttons' => [
                    'delivery'=>function($url,$model){
                        if($model->delivery_status == 0 && $model->pay_status == 1 && $model->order_status==1){

                            $options = [
                                'title' => '物流发货',
                                'class' => 'btn btn-warning btn-xs ajax-iframe-popup',
                                'style' => 'margin-bottom: 3px;',
                                'data-iframe'   => "{width: '500px', height: '320px', title: '物流发货'}",
                            ];
                            return  Html::a('<span class="fa fa-edit"></span> 物流发货', $url, $options);
                        }
                    },
                    'update'=>function($url,$model){
                        if($model->pay_status == 0 && $model->order_status==1){

                            $options = [
                                'title' => '修改金额',
                                'class' => 'btn btn-success btn-xs ajax-iframe-popup',
                                'style' => 'margin-bottom: 3px;',
                                'data-iframe'   => "{width: '600px', height: '380px', title: '修改金额'}",
                            ];
                        
                            return  Html::a('<span class="fa fa-edit"></span> 修改金额', $url, $options);
                        }
                    },
                    'view'=>function($url,$model){
                        $options = [
                            'title' => Yii::t('yii', 'View'),
                            'class' => 'btn btn-info btn-xs ajax-iframe-popup',
                            'data-iframe'   => "{width: '1100px', height: '90%', title: '订单详细',scrollbar:'Yes',btn:false,shadeClose:true}",
                        ];
                        return Html::a('<span class="fa fa-eye"></span> 订单详细', $url, $options);
                    }
                ],

            ],
        ],
    ]);
   ?>
    </div>
</div>
