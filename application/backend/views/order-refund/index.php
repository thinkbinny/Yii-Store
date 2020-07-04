<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '退货退款';
$this->params['breadcrumbs'][] = ['label' => '订单管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = ['label' => '售后管理', 'url' => 'javascript:;'];
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
.order-table .goods-detail .goods-info{width: 250px;height: 72px;}
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
            $date       = "<span>申请日期：". date('Y-m-d H:i:s',$model->created_at) ."</span>";
            $html  = '<tr ><td class="order_row" colspan="7"></td></tr>';
            $html .='<tr><td class="order_sn" colspan="7" >'. $order_sn. $date. '</td></tr>';
            return $html;
        },
        'columns' => [
            [
                'header'    => '商品信息',
                'format'    => 'raw',
                'options'   => ['width'=>350],
                'contentOptions'   => ['class'=>'goods-detail'],
                'value'     => function($model){
                    $goods = $model->goods;
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
                'header'=>'实付款',
                'format' => 'raw',
                'options'=>['width'=>80,],

                'value'=>function($model){
                    $html  = Html::tag('p','￥'.$model->refund_money);

                    return $html;
                },
            ],
            [
                'header'=>'退回金额',
                'format' => 'raw',
                'options'=>['width'=>80,],

                'value'=>function($model){
                    $html  = Html::tag('p','￥'.$model->refund_deposit);

                    return $html;
                },
            ],


            [
                'header'=>'买家',
                'format' => 'raw',
                'options'=>['width'=>120,],
                "contentOptions" => function($model){

                    return ['rowspan' => 1];
                },
                "value"=>function($model){
                    $html  = Html::tag('p',$model->getNicknameText());
                    $html  .= Html::tag('p',"(用户id：{$model->uid})",['class'=>'link-text']);
                    return $html;
                }
            ],
            [
                'header'=>'售后类型',
                'format' => 'raw',
                'options'=>['width'=>80,],
                "contentOptions" => function($model){
                    return ['rowspan' => 1];
                },
                "value"=>function($model){
                    return $model->getTypeText();
                }
            ],
            /*[
                'header'=>'申请日期',
                'format' => ['date','Y-MM-dd H:i:ss'],
                'options'=>['width'=>80,],
                'attribute' => 'created_at',
            ],*/
            [
                'header'    =>'处理状态',
                'format'    => 'raw',
                'options'   =>['width'=>80,],

                "value"=>function($model){
                    return $model->getStatusText();
                }
            ],


            [
                'options'=>['width'=>140,],
                'class' => 'backend\grid\ActionColumn',

                'header' => Yii::t('backend', 'Operate'),
                'template' => '{orderview} {view}',// {delete}
                'buttons' => [


                    'orderview'=>function($url,$model){
                        $url = Url::to(['order/view','id'=>$model->order_id]);
                        $options = [
                            'title' => Yii::t('yii', 'View'),
                            'class' => 'btn btn-info btn-xs ajax-iframe-popup',
                            'data-iframe'   => "{width: '1100px', height: '90%', title: '订单详细',scrollbar:'Yes',btn:false,shadeClose:true}",
                        ];
                        return Html::a('<span class="fa fa-eye"></span> 订单详细', $url, $options);
                    },
                    'view' => function($url,$model){

                        $options = [
                            'title' => Yii::t('yii', 'View'),
                            'class' => 'btn btn-success btn-xs ajax-iframe-popup',
                        ];
                        if( $model->status >= 0 ){
                            $options['data-iframe'] = "{width: '900px', height: '90%', title: '退款/退货退款/换货',scrollbar:'Yes',shadeClose:true}";
                        }else{
                            $options['data-iframe'] = "{width: '900px', height: '90%', title: '退款/退货退款/换货',scrollbar:'Yes',btn:false,shadeClose:true}";
                        }
                        return Html::a('<span class="fa fa-eye"></span> 查看', $url, $options);
                    }
                ],

            ],
        ],
    ]);
   ?>
    </div>
</div>
