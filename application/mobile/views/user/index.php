<?php
use yii\helpers\Url;
$this->title = '会员中心 ';

$js = <<<JS
$(function() {
    $.init()
    //$(".swiper-container").swiper(config);
});
JS;

$this->registerJs($js,\yii\web\View::POS_END);
?>
<style type="text/css">
.user-header{ height:7.2rem;background:#ff464e;
    background-image:url(https://jp.juancdn.com/jpwebapp_v1/images_v1/user/user_center_bg.png);
    background-position:bottom;background-repeat:no-repeat;background-size:100% auto;border-bottom:none;}
.user-header .bar{background: none;}
.user-header .bar::after{height: 0;}
.user-header .bar .button-link,.user-header .title{color: #fff;}
.user-header .user-login { padding-top: 2.2rem;position:relative;font-size:0;line-height:4.7rem;text-align:center;}
.user-header .user-login a {display:inline-block;font-size:20px;padding-left:48px;padding-right:48px;color:#fff;}
.user-header .user-login .line {position:absolute;top:50%;left:50%;margin-top: 1rem;height:.8rem;width:2px;background-color:#fff;transform:translate(-50%,-50%);}

.user-header .user-box-person {position:relative;color:#fff;overflow:hidden;}
.user-header .user-box-person .user {display:block;color:#fff; width:100%;margin-top:2.8rem;margin-bottom:0rem;overflow:hidden}
.user-header .user-box-person .head-img {width:2.8rem;height:2.8rem;border-radius:50%;float:left;margin-left:.768rem;border:2px solid #fff;overflow:hidden;}
.user-header .user-box-person .head-img img {width:100%;height:auto;}
.user-header .user-box-person .message {width:auto;float:left;margin-left:.512rem;}
.user-header .user-box-person .message p{margin:0;}
.user-header .user-box-person .message a {color:#fff;}
.user-header .user-box-person .message .tel{ font-size:.68267rem;color:#fff;height:.93867rem;line-height:.93867rem;}
.user-header .user-box-person .message .message-detal {width:100%;height:1.6rem;margin-top:.5rem;padding-right:.15rem;}
.user-header .user-box-person .message .message-detal span {
    position:relative;display:block;float:left;margin:0;font-size:.56rem;height:1.2rem;line-height:1.2rem;background:#f3414a;border:1px solid #ff8282;border-radius:.6rem;padding-left:.5rem;padding-right:.3rem;
    margin-right:.5rem;color:#fff;}
.user-header .user-box-person .message .message-detal span i{font-size:.38rem;padding-left: .2rem }
.user-body{margin-top:7.2rem; }
.user-body .user-order-all{font-size:.65rem;}
.user-body .user-order-all {display:block;position:relative;height:2rem;line-height:2rem;padding:0.2rem .6rem 0 .75rem;font-size:.7rem;background-color:#fff;color:#333;box-sizing:border-box;overflow:hidden;}
.user-body .user-order-all span{color:#333;}
.user-body .user-order-all .more{color:#999;}
.user-body .user-order-all .more .icon-right{padding-left: .2rem;font-size: .58rem}
.user-item {padding-top:.1rem;padding-bottom:.6rem;font-size:0;background-color:#fff;}
.user-item a {position:relative; display:inline-block; width:25%;font-size:12px; color:#666;text-align:center;vertical-align:top;}
.user-item .iconfont {display:block;margin:0 auto;font-size:1.5rem;}
.user-items .list-block{margin:0.3rem 0 0 0;font-size:.65rem;}
.user-items .list-block ul::before{background:none;}
</style>
<?=$this->render('../layouts/_nav.php')?>
<header class="user-header">
    <div class="bar bar-nav">
        <a class="button button-link button-nav pull-left back" href="javascript:window.history.go(-1)">
            <span class="icon icon-left"></span>
        </a>
        <a href="<?=Url::to(['userinfo'])?>" class="button button-link button-nav pull-right">
            <span class="icon icon-settings"></span>
        </a>
        <h1 class="title">个人中心</h1>
    </div>
    <div class="user-box-person">
        <div class="user">
            <div class="head-img">
                <a href="//m.juanpi.com/userinfo">
                    <img src="https://face1.juancdn.com/face/150101/0/0/default_204x204.jpg?iopcmd=thumbnail&amp;type=11&amp;height=80&amp;width=80%7Ciopcmd=convert&amp;Q=88&amp;dst=jpg">
                </a>
            </div>
            <div class="message">
                <p class="tel"><a href="//m.juanpi.com/userinfo">17776037759</a></p>
                <div class="message-detal">

                    <span><a href="//m.juanpi.com/userinfo">我的账户</a><i class="icon icon-right"></i></span>
                    <span >
					<a id="level" href="https://m.juanpi.com/userlevel/index">V0会员</a><i class="icon icon-right"></i>
					</span>
                </div>
            </div>
        </div>
    </div>
    <div style="display: none;" class="user-login">
        <a href="//m.juanpi.com/user/register">注册</a><i class="line"></i><a href="//m.juanpi.com/user/login">登录</a>
    </div>
</header>
<div class="content user-body">
    <div class="user-box">
        <a href="<?=Url::to(['order/index'])?>" class="user-order-all">
            <span>我的订单</span>
            <span class="more fr">全部订单<em class="icon icon-right"></em></span>
        </a>
        <div class="user-item">
            <!-- 新增横菜单 -->
            <a href="//m.juanpi.com/order/index/t/1">
                <em class="iconfont icon-daifukuan"></em><span class="text">待付款</span>					</a>
            <a href="//m.juanpi.com/order/index/t/13">
                <em class="iconfont icon-icon-test"></em><span class="text">待发货</span>					</a>
            <a href="//m.juanpi.com/order/index/t/14">
                <em class="iconfont icon-daishouhuo"></em><span class="text">待收货</span>					</a>
            <a href="//m.juanpi.com/refund/backlist">
                <em class="iconfont icon-tuihuo"></em><span class="text">退款/售后</span>
            </a>
        </div>
    </div>


    <div class="user-items">
        <div class="list-block">
            <ul>
                <li>
                    <a href="/demos/list/view-list" class="item-link item-content">
                        <div class="item-inner">
                            <div class="item-title">我的优惠券</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="/demos/list/contacts-list" class="item-link item-content">
                        <div class="item-inner">
                            <div class="item-title">我的收藏</div>
                        </div>
                    </a>
                </li>

            </ul>
        </div>
        <div class="list-block">
            <ul>
                <li>
                    <a href="/demos/list/view-list" class="item-link item-content">
                        <div class="item-inner">
                            <div class="item-title">客服中心</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="/demos/list/contacts-list" class="item-link item-content">
                        <div class="item-inner">
                            <div class="item-title">关于卷皮</div>
                        </div>
                    </a>
                </li>

            </ul>
        </div>
    </div>
    <?php
   /* $volist = $dataProvider->getModels();
    foreach ($volist as $vo){
        */?><!--
        <a href="<?/*=\yii\helpers\Url::to(['goods/view','id'=>$vo->id])*/?>">

            <div class=""><?/*=$vo->title*/?></div>
        </a>
        --><?php
/*    }*/
    ?>



</div>