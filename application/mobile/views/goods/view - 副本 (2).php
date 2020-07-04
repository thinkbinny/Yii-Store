<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;
$this->title = '网站首页';
$this->registerJsFile('//g.alicdn.com/msui/sm/0.6.2/js/sm-extend.min.js', [ 'depends' => 'assets\mobileAsset']);
$this->registerJsFile('/assets/static/js/vue.min.js', [ 'depends' => 'assets\mobileAsset']);
$this->registerCssFile('//g.alicdn.com/msui/sm/0.6.2/css/sm-extend.min.css');
$this->registerJsVar('goods_id',$model['id']);
$this->registerJsVar('UrlCart',Url::to(['cart/join']));
$this->registerJsVar('UrlBuy',Url::to(['goods/order-submit']));
$attrVolist = $model['attr'];//print_r($attr[0]['params']);//exit;
$attr = array();



foreach ($attrVolist as $val){
    $val['params'] = Json::decode($val['params'],true);
    $attr[] = $val;
}
//print_r($attr);exit;
$attr = Json::encode($attr);

$js = <<<JS
 var sku = JSON.parse('{$sku}');
 var attr = {$attr};
console.log(sku);
//console.log(attr);

var data = sku;
var res = {};
var spliter = '\u2299';
var r = {};
var keys = [];
var selectedCache = [];
/**
 * 计算组合数据
 */
function combineAttr(data, keys) {
    var allKeys = []
    var result = {}

    for (var i = 0; i < data.length; i++) {
        var item = data[i]
        var values = []

        for (var j = 0; j < keys.length; j++) {
            var key = keys[j]
            if (!result[key]) result[key] = []
            if (result[key].indexOf(item[key]) < 0) result[key].push(item[key])
            values.push(item[key])
        }
        allKeys.push({
            path: values.join(spliter),
            skuId: item['skuId']
        })
    }
    return {
        result: result,
        items: allKeys
    }
}
function init(data) {
        res = {};
        r = {};
        keys = [];
        selectedCache = [];
        for (var attr_key in data[0]) {
            if (!data[0].hasOwnProperty(attr_key)) continue;
            if (attr_key !== 'skuId' && attr_key !== 'stock_num' && attr_key !== 'price' && attr_key !== 'line_price' && attr_key !== 'pic_url') keys.push(attr_key)
        }
        //console.log(keys); 
        console.log(r); 
        //setAllTitle();
        r = combineAttr(data, keys);
        console.log(r); 
        /*render(r.result);
        buildResult(r.items);
        updateStatus(getSelectedItem());
        showResult();
        bindEvent();*/
    }

    init(data)











new Vue({
  el: '#pageGroupSku',
  data: {
     skuForm : {
          sku_id             : 0, 
          sku_price          : 0, 
          sku_line_price     : 0, 
          sku_pic_url        : '', 
          sku_sku_attr_id    : 0, 
          sku_stock_num      : 0, 
     }, 
     skuStock: sku,
     skuAttr : attr,
     skuData:{},
     skuCount:attr.length,
     skuHtml:'请选择：'
  },
  methods:{
      onSelectSkuClass:function(index,attr_value_id) {
          var _this       = this;
         
          if(_this.skuData[index] === undefined){
             return '';
          }else if(_this.skuData[index].attr_value_id===attr_value_id){
              
             return 'sel'; 
          }
          
          //return 'sel'; 
      },
      onSelectSkuEvent:function(index,i) {
        var _this       = this;        
        var skuAttr     = _this.skuAttr[index];
        var skuSelect   = {
            attr_id         :skuAttr.attr_id,
            attr_name       :skuAttr.attr_name,
            attr_value_id   :skuAttr.params[i].attr_value_id,
            attr_value_name :skuAttr.params[i].attr_name_value,
        };
        Vue.set(_this.skuData,index,skuSelect);
        _this._skuStockNum();//统计库存            
          var html  = '已选：';
          var count = 0;
          var attr_value_id_params = '';
          for (var vo in _this.skuData){
              count++;
              var value = _this.skuData[vo];
              html += '“'+value.attr_value_name+'” '
              if(attr_value_id_params!==''){
                attr_value_id_params+='_';  
              }
              attr_value_id_params += value.attr_value_id;
          }    
         
          //算出库存
          if(count ===  _this.skuCount){
          //_this.skuStock[attr_value_id_params].stock_num 当前SKU库存 
              var sku = _this.skuStock[attr_value_id_params];
              _this.skuForm = {
                  sku_id             : sku.id, 
                  sku_price          : sku.price, 
                  sku_line_price     : sku.line_price, 
                  sku_pic_url        : sku.pic_url, 
                  sku_sku_attr_id    : sku.sku_attr_id, 
                  sku_stock_num      : sku.stock_num, 
              }
             
              //console.log(_this.skuForm);
          }
          _this.skuHtml = html;     
      },
      //统计库存
      _skuStockNum:function(){
         var _this      = this;
         var skuCount   = _this.skuCount;//console.log(skuCount);
         var skuStock   = _this.skuStock;//查询库存
         var skuData    = _this.skuData;
         //var attr = new Array(); 
         for (var item in _this.skuData){ 
             
             /*
             if(item>0){
                attr +=  '_'+_this.skuData[item].attr_value_id;
             }else{
                attr +=  _this.skuData[item].attr_value_id;
             }
             */
         }
         //console.log(attr);
         var noStock = new Array();
         for (var i in skuStock){ 
             if(parseInt(skuStock[i].stock_num) === 0){
                 noStock.push({
                    sku_attr_id: skuStock[i].sku_attr_id
                 });
                 //console.log(skuStock[i].sku_attr_id);
             }
         }
         console.log(noStock);
      }
      
  }
  
});


$(function() {
    $(".swiper-container").swiper({
       /* autoplay: {
            delay: 1000,
        },*/
      pagination: '',
        onInit:function(swiper) { //console.log(swiper);
          var index = swiper.activeIndex+1;
          var count = swiper.imagesLoaded;
          $('.swiper-pagination').html(index+'/'+count)
        },
        onSlideChangeEnd: function(swiper){
          var index = swiper.activeIndex+1;
          var count = swiper.imagesLoaded;
          $('.swiper-pagination').html(index+'/'+count)
          
        }
    });
    
   /*$('.sku-info ul li').click(function(){
       var _this = $(this);
       if(_this.attr('class') !== 'disabled'){
           _this.addClass('sel');
           _this.siblings().removeClass('sel');
       }
    });*/
});

JS;
$this->registerJs($js,\yii\web\View::POS_END);

     $skuInfo = '';
     foreach ($model['attr'] as $vo){
         $value = Json::decode($vo['params'],true);
         $html  = Html::tag('h2',$vo['attr_name']);
         $li    = '';
         foreach ($value as $v):
             $li .= Html::tag('li',$v['attr_name_value'],['class'=>'normal']);
         endforeach;
         $html .= Html::tag('ul',$li);
         $skuInfo .= Html::tag('div',$html);
     }

            /*<div>
                <h2>规格</h2>
                    <ul>
                        <li class="sel">37*30*22cm</li>
                        <li class="disabled">43*34*26 cm</li>

                        <li class="normal">37*26*18 cm</li>
                    </ul>
                </div>
            <div>
                <h2>颜色分类</h2>
                    <ul>

                        <li class="normal">PVC塑料款栗棕色</li>

                    </ul>
            </div>*/
$html = <<<HTML
<div id="pageGroupSku" class="popup sku-container popup-sku">
    <div class="main-info">
        <div class="iconfont icon-guanbi close-popup"></div>
        <img src="https://s5.mogucdn.com/mlcdn/c45406/180821_50h4glf6c427j00b5fdd3c30aec71_640x960.jpg_128x999.jpg" class="sku-image"> 
            <div class="right-origin">
                <div >
                    <span class="now-price">
                        <span class="unit">¥</span>                        
                        <span class="num">{{skuForm.sku_price}}</span>
                    </span> 
                </div> 
                <div class="stock">库存 {{skuForm.sku_stock_num}} 件</div> 
                <div class="sku-text">{{skuHtml}}</div><!--请选择：颜色 尺码-->
            </div>
        </div>
    <div class="content">
        <div class="sku-info">
            <div v-for="(rows, index) in skuAttr">
                <h2>{{rows.attr_name}}</h2>
                <ul>
                    <li v-for="(vo,i) in rows.params" class="normal" @click="onSelectSkuEvent(index,i);" :class="onSelectSkuClass(index,vo.attr_value_id)" >{{vo.attr_name_value}}</li>                 
                </ul>
            </div>
        <div class="quantity-info">
            <div class="sku-quantity">
                <h2>购买数量 <span></span></h2>
                <p class="btn-minus iconfont icon-jian off">
                    <a class="btn minus" min=""></a>
                </p>
                <p class="btn-input"><input type="tel" value="1"></p>
                <p class="btn-plus iconfont icon-jia">
                    <a class="btn plus" max=""></a>
                </p>
           </div>
    </div>
  
</div>
       
        
    </div>
     <!--按钮-->
      <div class="sku-bottom">  
        <div class="sku-btns">
            <div class="sku-btn addcart">加入购物车</div>
            <div class="sku-btn gobuy">立即购买</div>
        </div> 
      </div>  
      <!--End 按钮-->  
</div>
HTML;


$this->params['content'] = $html;
    //endBody
?>
<style type="text/css">
    .sku-container{height:60%;bottom:0;top:auto;overflow: visible;}
    .sku-container .content{margin-top: 4.6rem;margin-bottom: 2.5rem}
    .sku-container .main-info{background: #fff;padding: 0.5rem 0.15rem 0;height: 4.6rem;position: relative;}
    .sku-container .main-info .icon-guanbi{position: absolute;right: .1rem;font-size: 1.2rem;top: 0rem;padding: 0 .3rem}
    .sku-container .sku-image {
        height: 4.6rem;width: 4.6rem;margin-left: 0.8rem;border-radius: 0.12rem;
        box-shadow: 0 0.12rem 0.16rem 0 rgba(0,0,0,.1);position: absolute;top: -0.6rem;background: white;}
    .sku-container .right-origin {margin-left:6.2rem;color: #333333; }

    .sku-container .right-origin .now-price {font-size: 1.2rem;line-height: 1.6rem;}
    .sku-container .right-origin .now-price .unit {font-size: .62rem;vertical-align: middle; position: relative; top: -0.35rem;color: #333; }
    .sku-container .right-origin .old-price {color: #999; text-decoration: line-through; }
    .sku-container .right-origin .stock {font-size: 0.56rem;}
    .sku-container .right-origin .sku-text {font-size: 0.56rem;color: #333333;text-overflow: ellipsis;white-space: nowrap;overflow: hidden;max-width: 12rem; }

    .sku-container .sku-info {-webkit-box-flex: 1;-webkit-flex: 1;flex: 1;overflow: auto;padding: 0 .6rem;}
    .sku-container .sku-info > div {margin-top: 0.2rem;border-top: 0.005rem solid #f2f2f2;}
    .sku-container .sku-info > div:first-child {border-top: 0;margin-top: 0;}
    .sku-container .sku-info > div h2 {font-size: 0.65rem;font-weight: normal;padding: 0.3rem 0;margin: 0;}
    .sku-container .sku-info > div ul {display: -webkit-box;display: -webkit-flex;display: flex;-webkit-box-orient: horizontal; -webkit-box-direction: normal;-webkit-flex-direction: row;flex-direction: row;-webkit-flex-wrap: wrap;flex-wrap: wrap;padding: 0;margin: 0;}
    .sku-container .sku-info > div ul li { list-style: none;border-radius: 0.6rem;padding: 0.2rem 0.38rem;font-size: 0.56rem;margin-right: 0.44rem;margin-bottom: 0.4rem;}
    .sku-container .sku-info > div ul li.normal {background-color: #F8F8F8;white-space: nowrap;}
    .sku-container .sku-info > div ul li.disabled {color: #999999;background-color: #F2F2F2;white-space: nowrap;}
    .sku-container .sku-info > div ul li.sel {color: #fff;background-image: -webkit-linear-gradient(left, #FF7A00 100%, #FE560A 100%);background-image: linear-gradient(to right, #FF7A00 100%, #FE560A 100%); }
    .sku-container .sku-info > div.quantity-info {padding-top: .5rem;}
    .sku-container .sku-info > div.quantity-info .sku-quantity {display: -webkit-box;display: -webkit-flex;display: flex;-webkit-box-orient: horizontal;-webkit-box-direction: normal;-webkit-flex-direction: row;flex-direction: row;-webkit-box-align: center;-webkit-align-items: center;align-items: center;}
    .sku-container .sku-info > div.quantity-info .sku-quantity h2 {-webkit-box-flex: 2;-webkit-flex: 2;flex: 2;}
    .sku-container .sku-info > div.quantity-info .sku-quantity p{margin: 0;}
    .sku-container .sku-info > div.quantity-info .sku-quantity .btn-minus,
    .sku-container .sku-info > div.quantity-info .sku-quantity .btn-plus {
        display: -webkit-box;display: -webkit-flex;display: flex;-webkit-box-pack: center;-webkit-justify-content: center;justify-content: center;
        -webkit-box-align: center;-webkit-align-items: center;align-items: center;position: relative;width: 1.3rem;height: 1.3rem;background-color: #f6f6f6;}
    .sku-container .sku-info > div.quantity-info .sku-quantity .btn-minus.off,
    .sku-container .sku-info > div.quantity-info .sku-quantity .btn-plus.off {background-color: #fbfbfb;color: #ccc;}
    .sku-container .sku-info > div.quantity-info .sku-quantity .btn-input {width: 2.1rem;line-height: 1;margin: 0 0.05rem;position: relative;text-align: center;}
    .sku-container .sku-info > div.quantity-info .sku-quantity .btn-input input {height: 1.3rem;line-height:1.3rem;font-size: 0.64rem;background-color: #f6f6f6;border: 0;width: 2rem;text-align: center;}
    .sku-container .sku-info > div.installment {border-top: 0; margin-top: 0;}
    .sku-container .sku-info > div.installment > div {overflow-x: auto;}
    .sku-container .sku-info > div.installment.disable ul > li {color: #999999;}
    .sku-container .sku-info > div.installment ul > li {background-color: #F8F8F8;border-radius: 0.1rem;text-align: center;font-size: 0.11rem;line-height: 0.13rem;}
    .sku-container .sku-info > div .sku-services h3 {
        color: #666666;
        margin: 0 12px 12px;
        font-size: 14px;
        font-weight: normal;
        background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyRpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoTWFjaW50b3NoKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo1NkI4NEU3RENGMkYxMUU1QkY3NUM2NTQ1N0RDQTg3NSIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDo1NkI4NEU3RUNGMkYxMUU1QkY3NUM2NTQ1N0RDQTg3NSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjU2Qjg0RTdCQ0YyRjExRTVCRjc1QzY1NDU3RENBODc1IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjU2Qjg0RTdDQ0YyRjExRTVCRjc1QzY1NDU3RENBODc1Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+C2iQoAAAAc9JREFUeNq8lj9IAlEcx+8eQkOLi4KLrgoauNXoGCTNztLZEOJSS3+GcrJFpCFNZ+cwcHRp11LIOQhBFxcHIbDvD74Xx+HlpVc/+IB39973+97zvd/v6dVqVfshYiADUiAO/Hw/BQPQAU3DMN6cBHQHgyi4BQeWd+9gxN8hELZ8ewKnMBrahdQS8RzoUfwFGCAIImCXRPjOYBtp26vVasYqgyK4B5/gCCTBA5gsGciE35JsK32qMLl2MhD3c/AB9kAdLLQVgWVZgDr7SN9L60yUZc0rYAb2QV/7ZcCkz76iUYFJ1GpQAlugsI64zaRArZJpIFsxDV5BQ9s8GtRKYxYxxX0ucedmzd38J9SSyCgeIolHzbswtVKKJ1QO0dgrdcxiTM244vEfad6HaPqV9sehmLhCf6AtmlPFrBhmbvEksD2D1BwoplyJQw9Hb2p1xKDJhxNJ3x6MXqeWRFMMpFi0wA7IejD6LLVaUojMXXQG5qAMEhuMPkGNOTW/k51UojzYBu11TCjepkberG72kllkTZgxKzZc5Cedy1Km+A3Er5wq2gU4Bj5Wqy6rVWCJcIDfumwrfXJW8X8p+j6HaQ9ZI+zXlrDl2vLs5tryJcAAAWuM+3fa4GAAAAAASUVORK5CYII=") right center no-repeat;
        background-size: auto 14px;
    }

    .sku-container .sku-bottom{position: absolute;bottom: 0;background: #fff;width: 100%;padding: .3rem .6rem;}
    .sku-container .sku-btns {
        display: -webkit-box;
        display: -webkit-flex;
        display: flex;
        -webkit-box-orient: horizontal;
        -webkit-box-direction: normal;
        -webkit-flex-direction: row;
        flex-direction: row;
        -webkit-box-pack: center;
        -webkit-justify-content: center;
        justify-content: center;
        -webkit-box-align: center;
        -webkit-align-items: center;
        align-items: center;
        box-sizing: border-box;
        padding-top: 0.1rem;
    }
    .sku-container .sku-btns .sku-btn {
        width: 50%;
        height: 1.8rem;
        line-height: 1.8rem;
        color: #fff;
        text-align: center;
        font-size: 0.72rem;
        cursor: pointer;
    }
    .sku-container .sku-btns .sku-btn.addcart {
        border-radius: 0.9rem 0 0 0.9rem;
        background-image: -webkit-linear-gradient(left, #FFC500, #FF9402);
        background-image: linear-gradient(to right, #FFC500, #FF9402);
    }
    .sku-container .sku-btns .sku-btn.gobuy {
        border-radius: 0 0.9rem 0.9rem 0;
        background-image: -webkit-linear-gradient(left, #FF7A00, #FE560A);
        background-image: linear-gradient(to right, #FF7A00, #FE560A);
    }
    .sku-container .sku-btns .sku-btn.disabled {color: rgba(255, 255, 255, 0.4);}



    .page .content{margin-bottom: 2.5rem !important;}
    .goods-detail .bar{background:none;top:1rem;}
    .goods-detail .bar::after{height: 0;}
    .goods-detail .bar a{background: rgba(0, 0, 0, 0.4);color: #fff;text-align:center;width: 1.8rem;height: 1.8rem;display:block;border-radius: .9rem;}
    .goods-detail .bar a .icon{padding:0;line-height:1.8rem;font-size: .9rem;}
    .goods-detail section .swiper-container{padding-bottom:0;}
    .goods-detail section .swiper-container .swiper-pagination{}
    .goods-detail section .swiper-container .swiper-slide img{width: 100%;height: 100%;}
    .goods-detail section .swiper-container .swiper-pagination{background: rgba(0, 0, 0, 0.4);width:auto;color: #fff;right: 1rem;left:inherit;line-height: 1.2rem;font-size: .7rem;padding: 0 .3rem;border-radius: .3rem}
    .goods-detail section .info-normal{padding:.3rem .8rem;background-color:#fff;}

    .goods-detail section .info-normal .price{-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-flow:row nowrap;-moz-box-orient:horizontal;-moz-box-direction:normal;-ms-flex-flow:row nowrap;flex-flow:row nowrap;-webkit-box-align:center;-webkit-align-items:center;-moz-box-align:center;-ms-flex-align:center;align-items:center;}
    .goods-detail section .info-normal .price-now,
    .goods-detail section .info-normal .price{display:-webkit-box;display:-webkit-flex;display:-moz-box;display:-ms-flexbox;display:flex}
    .goods-detail section .info-normal .price-now{-webkit-box-align:start;-webkit-align-items:flex-start;-moz-box-align:start; -ms-flex-align:start; align-items:flex-start;color:#333;}
    .goods-detail section .info-normal .price-now-unit{font-size:.72rem;line-height:1.8rem;display:inline-block;}
    .goods-detail section .info-normal .price-now-price{font-size:1.8rem;}
    .goods-detail section .info-normal .price-old {display:-webkit-box;display:-webkit-flex;display:-moz-box;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-flow:column nowrap;-moz-box-orient:vertical;
        -moz-box-direction:normal;-ms-flex-flow:column nowrap;flex-flow:column nowrap;margin-left:.5rem}
    .goods-detail section .info-normal .price-old-price{display:block;font-size:.58rem; text-decoration:line-through;color:#999}
    .goods-detail section .info-normal .price-old-tags{display:-webkit-box;display:-webkit-flex;display:-moz-box;display:-ms-flexbox;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-flow:row nowrap;-moz-box-orient:horizontal;-moz-box-direction:normal;-ms-flex-flow:row nowrap;flex-flow:row nowrap}
    .goods-detail section .info-normal .price-old-tag{display:block;margin-right:.5rem}
    .goods-detail section .info-normal .tag {font-size:.52rem;height:.7rem;line-height:.7rem;padding:0 .32rem;text-align:center;}

    .goods-detail section .info-normal .title-and-desc{padding-top:0rem;text-align:left; color:#333;background:#fff;}
    .goods-detail section .info-normal .title-wrap{display:-webkit-box;display:-webkit-flex;display:-moz-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-moz-box-align:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:justify;-webkit-justify-content:space-between;-moz-box-pack:justify;-ms-flex-pack:justify;justify-content:space-between;}
    .goods-detail section .info-normal .title-and-tag{-webkit-box-flex:1;-webkit-flex:1;-moz-box-flex:1;-ms-flex:1;flex:1;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;}
    .goods-detail section .info-normal .goods-title{font-size:.76rem;}
    .goods-detail section .info-normal .extra{color:#999;font-size: .6rem;padding-top: .3rem;}
    .goods-detail section .info-normal .extra .centre{text-align: center;}

    .goods-detail section .goods-item .list-block{margin:.38rem 0;font-size: .58rem;}
    .goods-detail section .goods-item .list-block ul::before{height: 0;}
    .goods-detail section .goods-item .item-title{color: #666}
    .goods-detail section .goods-item .item-text{color: #666;height:auto;line-height: normal;font-size: .58rem; }
    /**
    底部
     */
    .bottom-footer{font-size: .68rem;}
    .bottom-footer .bar{background: #fff;z-index: 50;}
    .bottom-footer .bar-tab::before{height: 0;}
    .bottom-footer .row{text-align: center;line-height: 2.5rem;}
    .bottom-footer .btn-red{background: linear-gradient(90deg,#ff5777,#ff468f);color: #fff;}
    .bottom-footer .btn-pink{background-color: #ffe6e8;color:#ff5777;}
</style>
<div class="bottom-footer">
    <div class="bar bar-tab">
        <div class="row no-gutter">
            <div class="col-20">收藏</div>
            <div class="col-40 btn-pink">加入购买车</div>
            <div class="col-40 btn-red">立即购买</div>
        </div>
    </div>
</div>




<div class="content" >
    <!-- 这里是页面内容区 -->

    <div class="goods-detail">
        <header class="bar bar-nav" >
            <a class="pull-left" href="javascript:window.history.go(-1)">
                <span class="icon icon-left"></span>
            </a>
            <a class="pull-right" href="/demos">
                <span class="icon icon-cart"></span>
            </a>
        </header>
    <section>
            <div class="swiper-container" data-space-between='10'>
                <div class="swiper-wrapper">
                    <div class="swiper-slide"><img src="//gqianniu.alicdn.com/bao/uploaded/i4//tfscom/i1/TB1n3rZHFXXXXX9XFXXXXXXXXXX_!!0-item_pic.jpg_320x320q60.jpg" alt=""></div>
                    <div class="swiper-slide"><img src="//gqianniu.alicdn.com/bao/uploaded/i4//tfscom/i4/TB10rkPGVXXXXXGapXXXXXXXXXX_!!0-item_pic.jpg_320x320q60.jpg" alt=""></div>
                    <div class="swiper-slide"><img src="//gqianniu.alicdn.com/bao/uploaded/i4//tfscom/i1/TB1kQI3HpXXXXbSXFXXXXXXXXXX_!!0-item_pic.jpg_320x320q60.jpg" alt=""></div>
                </div>
                <div class="swiper-pagination">0/0</div>
            </div>
    </section>
        <section>
        <div class="info-normal">
            <div class="price">
                <div class="price-now">
                    <span class="price-now-unit">¥</span>
                    <span class="price-now-price">28.00</span>
                    <span class="price-now-price">~</span>
                    <span class="price-now-price">36.40</span></div>
                <div class="price-old">
                    <span class="price-old-price" style="opacity: 0.7;">¥40.00起</span>
                    <div class="price-old-tags">
                        <span class="tag price-old-tag" style="background-color: rgb(255, 232, 238); color: rgb(255, 34, 85);">特惠中</span>
                    </div>
                </div>
            </div>

            <div class="title-and-desc clearfix">
                <div class="title-wrap">
                    <div class="title-and-tag">
                        <span class="goods-title">童装春秋小孩保暖男童女童秋衣秋裤儿童内衣套装纯棉宝宝睡衣全棉</span>
                    </div>
                </div>
                <div class="extra">
                    <div class="row no-gutter">
                        <div class="col-33">免邮费</div>
                        <div class="col-33 centre">月销:99</div>
                        <div class="col-33"></div>
                    </div>
                </div>
            </div>
        </div>
        </section>
        <section>
        <div class="goods-item">
            <div class="list-block">
                <ul>
                    <li>
                        <a href="/demos/list/view-list" class="item-link item-content">
                            <div class="item-inner">
                                <div class="item-title">服务</div>
                                <div class="item-text">7天无理由 · 付款后10天内发货</div>
                            </div>
                        </a>
                    </li>

                </ul>
            </div>
            <div class="list-block">
                <ul>
                    <li>
                        <a href="javascript:void(0);" class="item-link item-content open-popup" data-popup=".popup-sku">
                            <div class="item-inner">
                                <div class="item-title">规格</div>
                                <div class="item-text">请选择 颜色分类 尺码</div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="/demos/list/contacts-list" class="item-link item-content">
                            <div class="item-inner">
                                <div class="item-title">参数</div>
                                <div class="item-text">适用年龄 尺码...</div>
                            </div>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
        </section>
    </div>
</div>

