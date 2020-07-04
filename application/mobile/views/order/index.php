<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;
use common\components\Func;
use yii\widgets\ActiveForm;
$this->title = '全部订单';

$js = <<<JS
    
 
JS;

$this->registerJs($js,\yii\web\View::POS_END);//
?>
<header class="bar bar-nav">
    <a class="button button-link button-nav pull-left" href="javascript:history.go(-1)" data-transition='slide-out'>
        <span class="icon icon-left"></span>
    </a>
    <h1 class="title">全部订单</h1>
</header>

<style type="text/css">
.order-list{height:auto;}
.order-list .order-item{padding: .5rem .3rem;}
.order-list .order-item .order-item-order{background: #fff;border-radius: .3rem;margin-bottom: .5rem}
.order-list .order-item .order{padding: .3rem;}
.order-list .order-item .order-status{text-align: right;font-size: .66rem;padding-right: .5rem;padding-top:.5rem;padding-bottom: .5rem;color: #f60;}
.order-list .product-pic{}
.order-list .product-pic .product-image{width:3.2rem;height: 3.2rem;overflow: hidden;border-radius: .1rem;}
.order-list .product-pic .product-image img{width: 100%;}
.order-list .product-info{padding: 0 .5rem;font-size: .62rem;line-height: .7rem;}
.order-list .product-info .goods-title,.order-list .product-info .goods-attribute{text-overflow: ellipsis;overflow: hidden; -webkit-line-clamp: 2;display: -webkit-box;-webkit-box-orient: vertical;margin-bottom: .2rem;}
.order-list .product-info .goods-attribute{color:#999;font-size: .56rem;}
.order-list .product-price{width: 4rem;font-size: .6rem;padding-right: .3rem;text-align: right;}
.order-list .product-price p{margin: 0;}
.order-list .product-price .product-price-now{padding-top: .1rem;font-size: .78rem;font-weight: 700;}
.order-list .product-price .product-price-icon{font-size: .5rem;}
.order-list .product-price .product-number{color: #999;}
.order-list .order-total{text-align: right;padding-right: .5rem;font-size: .6rem;line-height: 2rem;height: 2rem;}
.order-list .order-total .price{font-size: .88rem;font-weight: 700;}
.order-list .order-alert{font-size: .68rem;color: #f60;line-height: 2rem;padding-left: .5rem;height: 2rem;}
.order-list .order-operation {padding:.5rem .5rem .5rem 0;font-size: .68rem;text-align: right}
.order-list .order-operation span{width: 4rem;margin-left: .6rem;display: inline-block;border: 1px solid #ddd;text-align: center;line-height: 1.5rem;border-radius: .75rem}
.order-list .order-operation span.f60{color: #f60;border-color: #f60;}
</style>

<div class="content" >

    <div class="buttons-tab">
        <a href="#tab1" class="tab-link active button">全部</a>
        <a href="#tab2" class="tab-link button">待付款</a>
        <a href="#tab3" class="tab-link button">待发货</a>
        <a href="#tab4" class="tab-link button">待收货</a>
        <a href="#tab5" class="tab-link button">售后</a>

    </div>
    <div class="">
        <div class="tabs">
            <div id="tab1" class="tab active">
                <div class="order-list">
                    <div class="order-item">
                        <?php
                        foreach ($volist as $vo):
                        ?>
                        <div class="order-item-order">
                            <div class="order-status"><?=$model->getOrderStatus($vo);?></div>
                            <?php

                            foreach ($vo['goods'] as $goods):
                            ?>
                            <div class="order">
                                <div class="product flex">
                                    <div class="product-pic">
                                        <div class="product-image">
                                            <img src="<?=Func::getImageUrl($goods['image_id'])?>">
                                        </div>
                                    </div>
                                    <div class="product-info flex-item">
                                        <div class="goods-title">
                                            <?php echo $goods['title'] ?>
                                        </div>
                                        <div class="goods-attribute">
                                            <?php
                                            echo $goods['sku_attr_name'];

                                            ?>

                                        </div>
                                    </div>
                                    <div class="product-price">
                                        <p class="product-price-now"><span class="product-price-icon">￥</span><?=$goods['price']?></p>
                                        <p class="product-number">×<?=$goods['quantity']?></p>
                                    </div>
                                </div>
                            </div>
                            <?php
                            endforeach;
                            ?>
                            <div class="order-total">共1件商品 合计：￥<span class="price"><?=$vo['pay_price']?></span></div>
                            <?php
                            if($vo['pay_status'] == 0 && $vo['order_status'] == 1 ){
                            echo Html::tag('div','订单即将关闭，建议尽快付款',['class'=>'order-alert']);
                            }
                            ?>
                            <div class="order-operation clearfix">
                                <?php
                                if($vo['order_status'] == 0 || $vo['receipt_status'] == 1){
                                    echo Html::tag('span','删除订单',[
                                        'class'=>'orderOperate',
                                        'data-url'=>Url::to(['delete','id'=>$vo['id']])
                                    ]);
                                }
                                if($vo['pay_status'] == 0 && $vo['order_status'] == 1 ) {
                                    echo Html::tag('span','取消订单',[
                                        'class'=>'orderOperate',
                                        'data-url'=>Url::to(['close','id'=>$vo['id']])
                                    ]);
                                    echo Html::tag('span', '付款', ['class' => 'f60']);
                                }
                                ?>

                            </div>
                        </div>
                        <?php
                          endforeach;
                        ?>
                    </div>



                </div>
            </div>
            <div id="tab2" class="tab">
                <div class="content-block">
                    <p>This is tab 2 content</p>
                </div>
            </div>
            <div id="tab3" class="tab">
                <div class="content-block">
                    <p>This is tab 3 content</p>
                </div>
            </div>
            <div id="tab4" class="tab">
                <div class="content-block">
                    <p>This is tab 4 content</p>
                </div>
            </div>
            <div id="tab5" class="tab">
                <div class="content-block">
                    <p>This is tab 5 content</p>
                </div>
            </div>
        </div>
    </div>


</div>

