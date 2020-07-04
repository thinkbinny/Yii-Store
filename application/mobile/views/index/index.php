<?php
$this->title = '网站首页';
$this->registerJsFile('//g.alicdn.com/msui/sm/0.6.2/js/sm-extend.min.js', [ 'depends' => 'assets\mobileAsset']);
$this->registerCssFile('//g.alicdn.com/msui/sm/0.6.2/css/sm-extend.min.css');
$js = <<<JS
$(function() {
    $.init()
    //$(".swiper-container").swiper(config);
});
JS;

$this->registerJs($js,\yii\web\View::POS_END);
?>
<style type="text/css">
.clearfix:after,.clearfix:before {content:"";display:table}
.clearfix:after {clear:both}
.clearfix {zoom:1}
.clearfix:after {display:block;content:"";clear:both;visibility:hidden}
.fl {float:left!important}
.fr {float:right!important}


.search-bar{height: 1.8rem;/*border-bottom: 1px solid #f2f2f2;*/padding-right:0;box-sizing: border-box;background-color:#fff;overflow: hidden;}
.search-bar .search-body{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;width:100%;height:100%;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;-webkit-align-items:center;-webkit-box-align:center;-ms-flex-align:center;align-items:center;}
.search-bar .search-text{padding-left: 0.2rem;position: relative;-webkit-flex:1;-ms-flex:1;flex:1;height:1.2rem;background-color:#f2f2f2;border-radius:10px;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-align-items:center;-webkit-box-align:center;-ms-flex-align:center;align-items:center;font-size:0.512rem;color:#666;}
.search-bar .icon-search{font-size:0.7rem;}
.search-bar .search-menu{width:2rem;height: 2rem;}
.search-bar .search-menu img{display: block;height: 100%;}
.swiper-body{padding-bottom:0;padding-top:1.8rem;background: #fff;}
.swiper-body img{width: 100%;}
.swiper-body .swiper-pagination-bullet-active{background:#fff;width:.6rem;border-radius:.2rem;}

.module-menu{padding-top: .5rem;background: #fff;}
.module-menu .res-item{text-align: center}
.module-menu .res-item .res-image{width:2.5rem;height: 2.5rem;margin: 0 auto;}
.module-menu .res-item .res-image img{width: 100%;height: 100%;display: block;}
.module-menu .res-item .res-title{color: rgb(102, 102, 102);font-size:0.6rem;height:1.5rem;line-height:1.5rem;font-weight:400;text-align:center;}

/**
goods-list
 */
.goods-list{width: 100%;margin-left:0;}
.goods-list .col-50{display: block;width: 47%;height: auto;margin: 2% 0 0 2%;background: #fff;border-radius: .06rem;overflow: hidden;}
.goods-list .goods-item .goods-img{width: 100%;height: 100%;overflow: hidden;position:relative;}
.goods-list .goods-item .goods-img img{width: 100%;/*display: block;position: absolute;top: 0;left: 50%;height: 100%;width: auto;transform: translateX(-50%);*/}
.goods-list .goods-item .goods-info{padding:0;}
.goods-list .goods-item .goods-info .box{margin: .3rem .32rem .1rem .32rem;height:auto;}
.goods-list .goods-item .goods-info .box .tags{height: 1.6rem;width: 100%;overflow: hidden;}
.goods-list .goods-item .goods-info .goods-title{width: 100%;line-height: .8rem;max-height: 1.6rem;font-size: .56rem;color:#333;overflow: hidden;height: 1.6rem;
    text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;}
.goods-list .goods-item .goods-info .goods-title .title-tag{display: inline-block;height: .72rem;line-height: .72rem;padding: 0 .12rem;margin-right: .12rem;font-size: .5rem;background: #eff3f6;color: #5a6f7a;}
.goods-list .goods-item .goods-info .bot-box{margin: 0 .36rem;}
.goods-list .goods-item .goods-info .bot-box p{margin:0;}
.goods-list .goods-item .goods-info .bot-box p.price{max-width: 45%;font-size: .72rem;font-weight: 700;color:#333;overflow: hidden;}
.goods-list .goods-item .goods-info .bot-box p.feed{ max-width:45%;height:.6rem;line-height:.6rem;margin-top:.2rem;padding-right:.72rem;
    background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAuCAAAAABLtcWwAAAAAmJLR0QA/4ePzL8AAAJVSURBVEjHjZULb6JAFIX5///ggNYXRdRq3fog2tVqo61r3DWRNU3MqkmNNru6oS0qa63Oig9AEOTEyNzL/WRmOHOlyCkp1Uikqpy8RZ1KTi+x0eXUNZDE9XC4+Vq7BEREFoQsomi5A+QLpqdee8yF7AoQ8HU3uIXgBuiC3e+PwuLXeWARgngY/0Tw31mgjIwepFE6B4wQkPRICmDoDHzyqBvjOvhPR6COxHHi+vgHjgC5U+bhHZmm6AVf7sgWQHoqbv3DPpgXWWO3vio+SRqgtG+DajIsNMbkhMYNIazeD+bbigq0vQAdzTcl4iCpmY/QgLdNqD6YTEsmLiSLWQZ96gsabqoPm3hDcZDcAxI4SkDNPVBDlhrg4tlt/bMfA4o8IvTHXf3vEB7V91AA52oZEofC9sWtsojMztfPosiudtZYJpFYnqtfJpBUi7ZeWsRws3KuX6UQ3Z6+nflm3KnzbpQAbjftvVtfg+qC7FVA8JUYATL2oWJfX4HvYGTtAPURtwfi6BMz8IKUPZDCyAJ0kbcHcuhaANHagnSV9eamAXUn19bwzQKUTjb3vVr64zVA0KdpVQ9ZCxDH2B6Y4MoChGgH/y3pkAVg/Ecli8VRGKDXJmAO3jiFO7+/ODEkeMxNwF9DF+6madCbT1rfhiQmJkBzxup7HPBV394efEDsx/6Y6N44ABPE1Mv0ftN7w+KHOv4QNy2Vvd/+vV/B7NY1i8p7J+cBUn196v0U4Ml13qsImRdNXhi1Q3vujEtVn1zyqHnG6lYyyHDJ5pxYpDSTXGaghf8BQmofv0UdUUcAAAAASUVORK5CYII=) center right no-repeat;
    background-size:auto .6rem;font-size:.62rem;color:#999;overflow:hidden}
</style>
<?=$this->render('../layouts/_nav.php')?>

<div class="content">
    <!-- 这里是页面内容区 -->
    <div class="bar search-bar">
       <div class="search-body">
           <a class="search-text" href="">
               <label class="icon icon-search" for="search"></label>
               <span>搜索</span>
           </a>
            <a class="search-menu" href="https://m.juanpi.com/cate/index">
                <img src="https://goods3.juancdn.com/bao/170421/4/9/58f9f3bca43d1f15ff678b8c_132x132.png">
            </a>
       </div>
    </div>

    <!-- Slider -->
    <div class="swiper-container swiper-body" data-space-between='10' data-autoplay="5000">
        <div class="swiper-wrapper">
            <div class="swiper-slide"><img src="https://goods5.juancdn.com/jas/200213/9/7/5e45118633b08947e64c124b_1080x418.png" alt=""></div>
            <div class="swiper-slide"><img src="https://goods3.juancdn.com/jas/191120/4/9/5dd50da433b0895fa61bc771_1080x418.jpg" alt=""></div>
            <div class="swiper-slide"><img src="https://goods5.juancdn.com/jas/190422/8/2/5cbd6608b6f8ea54ff237631_1080x418.png" alt=""></div>
        </div>
        <div class="swiper-pagination"></div>
    </div>
    <!--End Slider -->
    <div class="module-menu">
        <div class="row no-gutter">
            <div class="col-25 res-item">
                <a href="">
                    <div class="res-image">
                        <img src="https://s10.mogucdn.com/mlcdn/c45406/191021_341k4a24f2g43k2f831a3308lfb3e_135x135.jpg_640x640.v1cAC.40.webp">
                    </div>
                    <div class="res-title">最后疯抢</div>
                </a>
            </div>
            <div class="col-25 res-item">
                <a href="">
                    <div class="res-image">
                        <img src="https://s10.mogucdn.com/mlcdn/c45406/191021_7bk0111hd1j1j06d5043ai5bfddj9_135x135.jpg_640x640.v1cAC.40.webp">
                    </div>
                    <div class="res-title">限时秒杀</div>
                </a>
            </div>
            <div class="col-25 res-item">
                <a href="">
                    <div class="res-image">
                        <img src="https://s10.mogucdn.com/mlcdn/c45406/190627_453h1450k9j52k5fl1l1d33c40j5a_150x150.jpg_640x640.v1cAC.40.webp">
                    </div>
                    <div class="res-title">品牌特卖</div>
                </a>
            </div>
            <div class="col-25 res-item">
                <a href="">
                    <div class="res-image">
                        <img src="https://s10.mogucdn.com/mlcdn/c45406/191025_5289fkljd9d0g8i3752e1425h5k5j_150x150.gif_640x640.v1cAC.40.webp">
                    </div>
                    <div class="res-title">新人福利</div>
                </a>
            </div>
        </div>
    </div>

    <div class="goods-list row">
        <a href="" class="col-50 goods-item">
            <div class="goods-img">
                <img src="https://goods6.juancdn.com/goods/190306/b/e/5c7f4d5cb6f8ea19931b273a_800x800.jpg?imageMogr2/thumbnail/310x310!">
            </div>
            <div class="goods-info">
                <div class="box clearfix">
                    <div class="tags">
                        <div class="goods-title">
                            <span class="title-tag" style="background: #FF4466;color: #FFFFFF">限时快抢</span>
                            2020春装新款两面穿工装外套韩版时尚大码女装百搭连帽潮外套
                        </div>
                    </div>
                </div>
                <div class="bot-box clearfix">
                    <p class="price fl">¥47.42</p>
                    <p class="feed fr">4813</p>
                </div>
            </div>
        </a>
        <a href="" class="col-50 goods-item">
            <div class="goods-img">
                <img src="https://goods7.juancdn.com/goods/200112/c/7/5e1ad84cb6f8ea172439f4b4_800x800.jpg?iopcmd=thumbnail&type=11&height=310&width=310%7Ciopcmd=convert&Q=88&dst=webp">
            </div>
            <div class="goods-info">
                <div class="box clearfix">
                    <div class="tags">
                        <div class="goods-title">
                            <span class="title-tag" style="background: #FF4466;color: #FFFFFF">限时快抢</span>
                            2020春装新款两面穿工装外套韩版时尚大码女装百搭连帽潮外套
                        </div>
                    </div>
                </div>
                <div class="bot-box clearfix">
                    <p class="price fl">¥47.42</p>
                    <p class="feed fr">4813</p>
                </div>
            </div>
        </a>
        <a href="" class="col-50 goods-item">
            <div class="goods-img">
                <img src="https://goods6.juancdn.com/goods/190109/a/e/5c35f041b6f8ea61dd778c86_800x800.jpg?imageMogr2/thumbnail/310x310!/quality/88!/format/jpg">
            </div>
            <div class="goods-info">
                <div class="box clearfix">
                    <div class="tags">
                        <div class="goods-title">
                            <span class="title-tag" style="background: #FF4466;color: #FFFFFF">限时快抢</span>
                            2020春装新款两面穿工装外套韩版时尚大码女装百搭连帽潮外套
                        </div>
                    </div>
                </div>
                <div class="bot-box clearfix">
                    <p class="price fl">¥47.42</p>
                    <p class="feed fr">4813</p>
                </div>
            </div>
        </a>
        <a href="" class="col-50 goods-item">
            <div class="goods-img">
                <img src="https://goods7.juancdn.com/goods/190803/c/2/5d4509a1b6f8ea46122540ae_800x800.jpg?iopcmd=thumbnail&type=11&height=310&width=310%7Ciopcmd=convert&Q=88&dst=webp">
            </div>
            <div class="goods-info">
                <div class="box clearfix">
                    <div class="tags">
                        <div class="goods-title">
                            <span class="title-tag" style="background: #FF4466;color: #FFFFFF">限时快抢</span>
                            2020春装新款两面穿工装外套韩版时尚大码女装百搭连帽潮外套
                        </div>
                    </div>
                </div>
                <div class="bot-box clearfix">
                    <p class="price fl">¥47.42</p>
                    <p class="feed fr">4813</p>
                </div>
            </div>
        </a>
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