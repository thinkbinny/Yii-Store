<?php
use yii\helpers\Url;
$this->title = '网站首页';

$this->registerJsVar('goods_id',$model['id']);
$this->registerJsVar('UrlCart',Url::to(['cart/join']));
$this->registerJsVar('UrlBuy',Url::to(['goods/order-submit']));
$js = <<<JS

    var sku = JSON.parse('{$sku}');
//console.log(sku);
    $('.sku dd').click(function(){
       var _this = $(this);
       _this.addClass('active');
       _this.siblings().removeClass('active');
    });
    function fileSelect() {
       var li = $('.sku dd.active');
          var ids = '';
          li.each(function(){
             if(ids !== ''){
              ids += '_'; 
             }
              ids += $(this).attr('data-value');
          });
          return ids;
    }
    
    function cart() {
      var ids  = fileSelect();
      var attr = sku[ids];     
      var amount = $('#amount').val();
      if(attr === undefined){
          $.alert('请选择购买参数');
      }
      var data = {
          goods_id:goods_id,
          amount:amount,
          sku_id:attr.id        
      }
      ajax('POST',UrlCart,data,function(ret) {
         if(ret.status === true){
             $.toast(ret.message);
         }else{
             $.toast(ret.message);
         }
      })
    }
    function buy() {
      var ids  = fileSelect();
      var attr = sku[ids];     
      var amount = $('#amount').val();
      if(attr === undefined){
          $.alert('请选择购买参数');
      }
      var data = {
          goods_id:goods_id,
          amount:amount,
          sku_id:attr.id        
      }
      ajax('POST',UrlBuy,data,function(ret) {
         if(ret.status === true){
             $.toast(ret.message);
         }else{
             $.toast(ret.message);
         }
      })
    }
JS;
//var_dump($sku);
$this->registerJs($js,\yii\web\View::POS_END);//
?>
<header class="bar bar-nav">
    <a class="button button-link button-nav pull-left" href="javascript:history.go(-1)" data-transition='slide-out'>
        <span class="icon icon-left"></span>
        返回
    </a>
    <h1 class="title">我的生活</h1>
</header>
<nav class="bar bar-tab">
    <a class="tab-item active" href="#">
        <span class="icon icon-home"></span>
        <span class="tab-label">首页</span>
    </a>

    <a class="tab-item" href="#">
        <span class="icon icon-app"></span>
        <span class="tab-label">分类</span>
    </a>
    <a class="tab-item" href="#">
        <span class="icon icon-cart"></span>
        <span class="tab-label">购物车</span>
    </a>
    <a class="tab-item" href="#">
        <span class="icon icon-me"></span>
        <span class="tab-label">我</span>
    </a>
</nav>
<style type="text/css">
    .attr-sku{padding: 10px;}
    .attr-sku dt{display: inline-block;}
    .sku dd{cursor: pointer;}
    .attr-sku dd{display: inline-block;border: 1px solid #ddd;padding:3px 5px;margin: 0 3px;}
    .attr-sku dd.active{border: 1px solid #f60;background: #f60;color: #fff;}
</style>
<div class="content" style="margin: 0 auto;max-width: 750px;background: #fff;">
    <!-- 这里是页面内容区 -->

        <div class="" >
            <?php
            $image_id = current($model['image']);
            $pic_url  =  \common\components\Func::getImageUrl($image_id['image_id']);
            ?>
            <img style="width: 100%;" src="<?=$pic_url?>">
        </div>
        <div class="titles">
            <?php
            echo $model['title'];

            //var_dump($model['attr']);
            ?>
        </div>
        <div class="attr-sku">
            <div class="sku">
                <?php
                foreach ($model['attr'] as $vo){
                    $value = \yii\helpers\Json::decode($vo['params'],true);//var_dump($value);
                ?>
                <dl class="">
                    <dt><?=$vo['attr_name']?></dt><!--active-->
                    <?php
                    foreach ($value as $v):
                    ?>
                    <dd class="" data-value="<?=$v['attr_value_id']?>"><?=$v['attr_name_value']?></dd>
                    <?php
                    endforeach;
                    ?>
                </dl>
                <?php
                }
                ?>
            </div>
            <dl>
                <dt>数量</dt>
                <dd><input value="1" id="amount" type="number" style="border: none;"></dd>
            </dl>

        </div>
        <div class="">
            <button onclick="buy();" type="button">立即购买</button>
            <button onclick="cart()" type="button">加入购物车</button>
        </div>
        <div class="" style="height: 100px;">

        </div>
</div>