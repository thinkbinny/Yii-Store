<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$this->title = '确定订单';

$js = <<<JS
    
 
JS;
//var_dump($sku);
$this->registerJs($js,\yii\web\View::POS_END);//
?>
<header class="bar bar-nav">
    <a class="button button-link button-nav pull-left" href="javascript:history.go(-1)" data-transition='slide-out'>
        <span class="icon icon-left"></span>
        返回
    </a>
    <h1 class="title">确定订单</h1>
</header>

<style type="text/css">
    /**
    底部
     */
    .bottom-footer{font-size: .68rem;line-height:2.5rem; }
    .bottom-footer .bar{background: #fff;z-index: 50;}
    .bottom-footer .bar-tab::before{height: 0;}
    .bottom-footer .paybar-num{font-size: .6rem;color:#999;padding-right: .5rem}
    .bottom-footer .paybar-sum{font-size: .7rem;color:#333;padding-left: .5rem}
    .bottom-footer .paybar-icon{color: #f46;}
    .bottom-footer .paybar-sum-red{color: #f46;font-weight: 700;font-size:1rem;}
    .bottom-footer .paybar-btn{text-align: center;background-image: linear-gradient(90deg,#f46,#ff468f);color:#fff;width:5.5rem;}
    /**

     */


    .address{position:relative;width: 100%;min-height:1rem;line-height:.1rem;padding:.68rem 0 .68rem .48rem;margin:0 0 .5rem;font-size:.7rem;color:#333;background:#fff url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFgAAAAKCAYAAAAw05nIAAABxElEQVRIib3VMWsUQRwF8LkURkEbNQiCVqKSD2BpEdFvINhZGFCY3ZOzUbC5JsyOCSIBS0/uvzOTzSCHImxp5LQT7IM2s3NJwJgERSGccM9Cv8Bc8R94/ZsfwxshEg+AmawKj6QJY2kCWOLipG2bp3mN2eS+dT0LVXah6RCKwBJNYxR2Cd4fSyr7YG3vXGbiBhusCchN2JYuXE+FFUIIKFqAok02WEVAQR+w6ueTy2Y23JQ27HPiStu87r79eToZtufnUJR9VlhNe1DlIoBWUtnnHsczN+qxwpr4+34V7ibDAi0s051/l2XEVUTo+bnUvqJjd65IE7+wToILn7NX3y8n4676eSgzZIbdhKKFZFgAM+1q9Dh38Q8brouTzMZl73EkqevLjaMo7BI0jRnn4BCq7KKukz9d8dDvn89NM+R8tZlrttpro2upXfGkugFNX5m39h10eSkZVgghpG1u5SYcsOKaOOj4HyeTYJ+5M1DG8cKaXay421PB6o+7J3LX9Fm31sRfstpeTIIFWtDmHjQdMMJOUNAL9AenpsLt+G8XchffSxcbruSmGcrB6GISrn5zFtqsQ1HDFk2fsLJ+dSrY/+cvxmtJYHBvW44AAAAASUVORK5CYII=') repeat-x;background-size:6%}
    .address-location{position: absolute;margin: auto 0;top: .8rem;bottom: 0;width: 1rem;height: 1rem;font-size: 1.2rem}
    .address .address-content-wrap{
        padding-left: 2rem;display:block;
        width:100%;
        font-weight:500;
        line-height:2.2rem;
        color:#3d4145;
        text-align:left;
        padding-right:1.5rem;
        background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyhpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDIxIDc5LjE1NTc3MiwgMjAxNC8wMS8xMy0xOTo0NDowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTQgKE1hY2ludG9zaCkiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NUM0QzFDNzMyREM0MTFFNUJDNTI4OTMzMEE0RjBENzMiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NUM0QzFDNzQyREM0MTFFNUJDNTI4OTMzMEE0RjBENzMiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo1QzRDMUM3MTJEQzQxMUU1QkM1Mjg5MzMwQTRGMEQ3MyIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo1QzRDMUM3MjJEQzQxMUU1QkM1Mjg5MzMwQTRGMEQ3MyIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pjs2Bb4AAAItSURBVHjazJhbK0RRGIb3DIOU/AG5kUTOgxmHceFGKf6BO+Vf+E8KKYcYg3FuMpNIDhFJXJAcp/GtvKumrzVs+zBrvfU2u689q6d3rb33+lYgl8tZvymZ3JOX7eQp8gT50fJA0Wj4z3tKbY5VR14hV5ObyWLkZ6sICtq4p4V8CjihevIWucoUQJFUmtUayTvkShMAL5DiGqs3IMlK3YBSgwrIZkBWmAAoIRMKyG2/IIMO/hMjbygepCS53ARAoQHyOqu1YbrLTADMAXJbASmSDOkGlOpTQHaQN72CdAuYBeQuq4cBWaIbUEJGC0Am3UIGPVoqMsk9Vu/CwxTQDSj0iSQPWD2C6Q7oBhT6AmRKAZkwAVDoowBkn+LdqQVQ6A2QhwrIuAmAEjKi2KrF/jPdfgIKveI7Pcfq/eSMCYBSD4pakymA0+RxVrsn15oAOEMeY7Vbcif5ys4ApT7CzZJHWO2G3I1fSyfgPHmY1a7x6bvT/ZpZUMBdOoHzI8El8pCiK+wq8CQXNcFlBdw51tyD00G9SnAVHV++zgDn6hzHiwTjCrgTTKvrQya3Ca5jA5CvY3IP+UlnTxJEb8zhjpDck1cL20mCAcBFWD2D2ovOvjiERojDpTGtnsL9N8EQegt+LJrC5vRN59lMORp0DrePNH2BswvYivXVzuoHSO7dz+2QHcAa6+eMOl87WHOffm8m7QCK7foog+tFi2mZACg3npPkRUxrtkitgvUtwAA5A3LWdzPizwAAAABJRU5ErkJggg==);
        background-size:.7rem;
        background-repeat:no-repeat;
        background-position:97% center;
        background-position:-webkit-calc(100% - .5rem) center;
        background-position:calc(100% - .5rem) center
    }
    .address .address-contact{line-height:1.2rem;margin-bottom:.06rem;font-weight:700;}
    .address .address-content{font-size:.62rem;line-height:.8rem;}
    .shop{background: #fff;padding-bottom: 1rem;}
    .order-info .product{padding: .5rem;}

    .order-info .product-pic {width:3.8rem;height:3.8rem;}
    .order-info .product-pic img{width: 100%;height: 100%;}
    .product-info{}
    .product-info .product-des{padding-left: .5rem;padding-right: .5rem}
    .product-info .product-des .product-des-name{margin: 0;font-size: .6rem;display: -webkit-box;
        line-height: .8rem;
        margin-bottom: .1rem;
        text-overflow: ellipsis;
        overflow: hidden;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    .product-info .product-des .product-des-sku{display: -webkit-box;
        max-height: 1.5rem;
        line-height: .7rem;
        margin-bottom: .1rem;
        text-overflow: ellipsis;
        overflow: hidden;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        font-size: .56rem;
        color:#ababab;
        white-space: normal;
        word-break: break-all;}
    .product-price{text-align: right;font-size: .58rem;color:#333;width: 3rem}
    .product-price p{margin: .6em 0;}
    .product-price .product-price-origin{text-decoration: line-through;color:#ababab;}
    .product-price .product-price-now{line-height: .4rem; margin-bottom: .3rem;font-size: .68rem;font-weight: 700;}
    .product-price .product-number{font-size: .6rem;color:#999;}
    .list-item {padding: 0 .5rem;font-size: .68rem;height: 1.5rem;line-height: 1.5rem;margin-bottom: .2rem}
    .list-item .label{width: 4rem;}
    .list-item .flex-item{text-align: right;}
    .list-item .sku-quantity{text-align: right}
    .list-item .sku-quantity p{width: 1.3rem;height: 1.3rem;line-height: 1.3rem;margin: 0;text-align:center;background-color:#fff;color:#999;border: 1px solid #f6f6f6;}
    .list-item .sku-quantity .off{color:#eee;}
    .list-item .sku-quantity .btn-input{width: 2.1rem;line-height: 1;position: relative;border-left: none;border-right: none;text-align: center;}
    .list-item .sku-quantity .btn-input input{font-size: 0.64rem;border: 0;width: 2rem;line-height: 1.1rem;text-align: center;}
    .list-item .comment-text{width: 100%;background: #f7f7f7;border:none;border-radius:.2rem;height: 1.5rem;margin-left.1rem;font-size:.62rem;padding: .5rem}
    .total{padding-top: 1rem;padding-right: .5rem;
        -webkit-box-pack: end;
        -webkit-justify-content: flex-end;
        -moz-box-pack: end;
        -ms-flex-pack: end;
        justify-content: flex-end;font-size: .6rem;line-height: 1.5rem;
    }
    .total .total-num{margin-right:.5rem;color: #999;}
    .total .total-price{font-weight: 700;font-size: .86rem;color:#f46;}
</style>

<div class="content" >
    <div class="address">
       <i class="iconfont icon-weizhi address-location"></i>
        <div class="address-content-wrap">
            <div class="address-contact">赵开斌 15977795537</div>
            <div class="address-content">广西壮族自治区南宁市西乡塘区广西大学东门广西壮族自治区南宁市西乡塘区广西大学东门</div>
        </div>
    </div>
    <div class="order-info">
      <?php
      $form = ActiveForm::begin([
          'options'=>['class'=>'layui-form'],
      ]);
      ?>
        <div class="shop">
            <?php
            $i                = 0;
            $totalPrice       = 0;
            $shippingPrice    = 0;
            foreach ($model as $value):
                $i++;
                $price      = bcmul($value['price'],$value['quantity'],2);
                $totalPrice = bcadd($totalPrice,$price,2);
            ?>
            <div class="">
                <div class="product flex">
                    <div class="product-pic">
                            <img src="<?=$value['image_url']?>" alt="">
                    </div>
                    <div class="product-info flex-item">
                        <div class="product-des">
                            <h5 class="product-des-name"><?=$value['title']?></h5>
                            <div class="product-des-sku">
                                <?php
                                if(empty(!$value['attribute'])){
                                    foreach ($value['attribute'] as $attr){
                                        echo $attr['name'].'：'.$attr['value'].'；';
                                    }
                                }
                                ?>
                                </div>
                        </div>
                    </div>
                    <div class="product-price">
                        <p class="product-price-origin">¥<?=$value['price']?></p>
                        <p class="product-price-now">¥<?=$value['price']?></p>
                        <div class="product-number">x<?=$value['quantity']?></div>
                    </div>
                </div>
                <div class="list-item">
                    <div class="flex">
                        <div class="label">购买数量</div>
                        <div class="flex-item">
                            <div class="sku-quantity" style="">
                                <div class="flex pull-right">
                                    <p class="btn-minus iconfont icon-jian off"></p>
                                    <p class="btn-input"><input  type="tel" value="<?=$value['quantity']?>"></p>
                                    <p class="btn-plus iconfont icon-jia"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            endforeach;
            $shippingPrice = bcadd($shippingPrice,0,2);
            $totalPrice    = bcadd($shippingPrice,$totalPrice,2);
            ?>
            <div class="list-item">
                <div class="flex">
                    <div class="label">快递运费</div>
                    <div class="flex-item">
                        ¥<?=$shippingPrice?>
                    </div>
                </div>
            </div>

            <div class="list-item">
                <div class="flex">
                    <div class="label">订单备注</div>
                    <div class="flex-item ">
                        <input type="text" placeholder="有什么想对商家说的可以写在这里哦~" class="comment-text">
                    </div>
                </div>
            </div>

            <div class="flex total">
                <div class="total-num">共<?=$i;?>件</div>
                <div>合计：</div>
                <div class="total-price">¥<?=$totalPrice?></div></div>
            </div>
      <?php
      ActiveForm::end();
      ?>
    </div>
</div>
<div class="bottom-footer">
    <div class="bar bar-tab">
        <div class="flex">
            <div class="flex-item paybar-sum">总价：
                <span class="paybar-icon">¥</span>
                <span class="paybar-sum-red"><?=$totalPrice?></span>
            </div>
            <div class="paybar-num">共<?=$i;?>件商品</div>

            <div class="paybar-btn">提交订单</div>
        </div>
    </div>
</div>
