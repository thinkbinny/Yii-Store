<?php
use yii\helpers\Html;
$this->title = '订单详细';
$css = <<<CSS
body{padding: 10px;}
.order-body{background: #fff;padding: 20px;border-radius:7px;}
.order-body .order-progress {padding-top: 20px;}
/* 订单详情 */
.order-body .order-detail-progress{ height: 26px;line-height: 26px;background: #f8f8f8;border-radius: 13px;font-size: 14px;text-align: center;position: relative;margin-bottom: 70px; }
.order-body .order-detail-progress:before, .order-body .order-detail-progress:after {content: "";position: absolute;z-index: 2;left: 0;top: 0;bottom: 0;border-radius: 13px;background: #54aff5; }
.order-body .order-detail-progress:after {background: #8ccdff;z-index: 1; }
.order-body .order-detail-progress.progress-1:before {width: 0; }
.order-body .order-detail-progress.progress-1:after {width: 20%; }
.order-body .order-detail-progress.progress-2:before {width: 20%; }
.order-body .order-detail-progress.progress-2:after {width: 40%; }
.order-body .order-detail-progress.progress-3:before {width: 40%; }
.order-body .order-detail-progress.progress-3:after {width: 60%; }
.order-body .order-detail-progress.progress-4:before {width: 60%; }
.order-body .order-detail-progress.progress-4:after {width: 80%; }
.order-body .order-detail-progress.progress-5:before {width: 100%; }
.order-body .order-detail-progress.progress-5:after {width: 100%; }
.order-body .order-detail-progress.progress-5 li:nth-child(5) {color: #fff; }
.order-body .order-detail-progress li {width: 20%;float: left;border-radius: 13px;position: relative;z-index: 3; }
.order-body .order-detail-progress .tip {font-size: 12px;padding-top: 10px;color: #8c8c8c; }
.order-body .order-detail-progress.progress-1 li:nth-child(1),.order-body .order-detail-progress.progress-2 li:nth-child(1),.order-body .order-detail-progress.progress-3 li:nth-child(1),.order-body .order-detail-progress.progress-4 li:nth-child(1),.order-body .order-detail-progress.progress-5 li:nth-child(1) {color: #fff;}
.order-body .order-detail-progress.progress-2 li:nth-child(2),.order-body .order-detail-progress.progress-3 li:nth-child(2),.order-body .order-detail-progress.progress-4 li:nth-child(2),.order-body .order-detail-progress.progress-5 li:nth-child(2) {color: #fff; }
.order-body .order-detail-progress.progress-3 li:nth-child(3),.order-body .order-detail-progress.progress-4 li:nth-child(3),.order-body .order-detail-progress.progress-5 li:nth-child(3) {color: #fff; }
.order-body .order-detail-progress.progress-4 li:nth-child(4),.order-body .order-detail-progress.progress-5 li:nth-child(4) {color: #fff; }
.order-body .td__order-price {width: 200px;display: inline-block; }



/**表格**/
.order-table p{padding:2px 0;overflow: hidden;max-height:22px;text-overflow:ellipsis;margin: 0;}
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
.order-table tbody > tr > td > dl{margin-bottom:2px;}
.order-table tbody > tr > td > dl > dt{font-weight:normal !important;display: inline-block;width:90px;text-align: right;}
.order-table tbody > tr > td > dl > dd{display: inline-block;width: 90px;text-align: right;}
.order-table .color-red{color:#f00 !important;}
fieldset{margin-top:50px !important;}
.layui-elem-field legend{position: relative;padding:0 10px 0 25px;}
.layui-elem-field legend::before{content:'';position: absolute;width:4px;height:15px;top:6px;left:10px;background:#1E9FFF;}
CSS;
$this->registerCss($css);
?>
<div class="order-body">
    <div class="order-progress clearfix">
        <?php
            $progress = 2;
            if($model->pay_status == 1){
                $progress = $progress +1;
            }
            if($model->delivery_status == 1){
                $progress = $progress +1;
            }
            if($model->receipt_status == 1){
                $progress = $progress +1;
            }
        ?>
        <ul class="order-detail-progress progress-<?=$progress?>">
            <li>
                <span>下单时间</span>
                <div class="tip"><?=date('Y-m-d H:i:s',$model->created_at)?></div>
            </li>
            <li>
                <span>付款</span>
                <?
                if($model->pay_status == 1){
                    echo Html::tag('div','付款于 '.date('Y-m-d H:i:s',$model->pay_time),['class'=>'tip']);
                }
                ?>
            </li>
            <li>
                <span>发货</span>
                <?
                if($model->delivery_status == 1){
                    echo Html::tag('div','发货于 '.date('Y-m-d H:i:s',$model->delivery_time),['class'=>'tip']);
                }
                ?>
            </li>
            <li>
                <span>收货</span>
                <?
                if($model->receipt_status == 1){
                    echo Html::tag('div','收货于 '.date('Y-m-d H:i:s',$model->receipt_time),['class'=>'tip']);
                }
                ?>
            </li>
            <li>
                <span>完成</span>
                <?
                if($model->receipt_status == 1){
                    echo Html::tag('div','发货于 '.date('Y-m-d H:i:s',$model->receipt_time),['class'=>'tip']);
                }
                ?>
            </li>
        </ul>
    </div>
    <!--基本信息-->
    <fieldset class="layui-elem-field layui-field-title" style="margin-top:0 !important;">
        <legend>基本信息</legend>
    </fieldset>
    <table class="layui-table order-table">
        <colgroup>
            <col width="200">
            <col >
            <col width="240">
            <col width="100">
            <col width="100">
            <col width="180">

        </colgroup>
        <thead>
        <tr>
            <th>订单号</th>
            <th>买家</th>
            <th>订单金额</th>
            <th>支付方式</th>
            <th>配送方式</th>
            <th>交易状态</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?=$model->order_sn?></td>
            <td>
                <p><?=$model->getNicknameText();?></p>
                <p class="link-text">(用户id：<?=$model->uid?>)</p>
            </td>
              <td>
                    <dl>
                        <dt>订单总额：</dt><dd> ￥<?=$model->total_price?></dd>
                    </dl>

                    <dl>
                        <dt>实付款金额：</dt><dd class="color-red"> ￥<?=$model->pay_price?></dd>
                    </dl>

            </td>
            <td><?=$model->getPayTypeText();?></td>
            <td><?=$model->getDeliveryTypeText();?></td>
            <td>
                <?php

                $html = '';

                $html .= Html::tag('p','付款状态：'.$model->getPayStatusText());
                $html .= Html::tag('p','发货状态：'.$model->getDeliveryStatusText());
                $html .= Html::tag('p','收货状态：'.$model->getReceiptStatusText());

                $span  = $model->getOrderStatusText();
                if(!empty($span)){
                    $html .= Html::tag('p','订单状态：'.$span);
                }
                echo $html;
                ?>
            </td>
        </tr>
        </tbody>
    </table>

    <fieldset class="layui-elem-field layui-field-title "  >
        <legend>费用信息</legend>
    </fieldset>
    <table class="layui-table order-table">
        <colgroup>
            <col width="120">
            <col width="400">
            <col width="120">
            <col width="400">
        </colgroup>
        <thead>
            <tr>
                <th>名称</th>
                <th>金额</th>
                <th>名称</th>
                <th>金额</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: right;">订单总额：</td>
                <td>￥<?=$model->total_price?>  </td>
                <td style="text-align: right;">运费金额：</td>
                <td>+￥<?=$model->shipping_price?>  </td>
            </tr>
            <tr>
                <td style="text-align: right;">优惠金额：</td>
                <td>-￥<?=$model->coupon_money?> </td>
                <td  style="text-align: right;">红包金额：</td>
                <td>-￥<?=$model->redbags_money?>  </td>
            </tr>
            <tr>
                <td style="text-align: right;">积分抵消：</td>
                <td >
                    -￥<?=$model->integral_money?>
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: right;">修改金额：</td>
                <td class="color-red"><?php
                    if($model->update_price<0){
                        echo '-￥'.trim($model->update_price,'-');
                    }else{
                        echo '+￥'.$model->update_price;
                    }
                    ?> </td>
                <td style="text-align: right;">实付款金额：</td>
                <td class="color-red">￥<?=$model->pay_price?>  </td>
            </tr>
        </tbody>
    </table>

    <!--End 基本信息-->
    <!--商品信息-->
    <fieldset class="layui-elem-field layui-field-title "  >
        <legend>商品信息</legend>
    </fieldset>
    <table class="layui-table order-table">
        <colgroup><col >
            <col width="200">
            <col width="90">
            <col width="120">
            <col width="90">
            <col width="120">

        </colgroup>
        <thead>
        <tr>
            <th>商品名称</th>
            <th>商品编码</th>
            <th>重量(Kg)</th>
            <th>单价</th>
            <th>购买数量</th>
            <th>商品总价</th>
        </tr>
        </thead>
        <tbody>
        <?php
            foreach ($model->goods as $goods):
        ?>
        <tr>
            <td class="goods-detail">
                <?php
                $pic_url = $model -> getImageUrl($goods['image_id']);
                $img  = Html::tag('div','',['class'=>'pic_url','style'=>"background-image:url('{$pic_url}')"]);
                $attr = $goods['sku_attr_name'];
                $info = Html::tag('p',$goods['title'],['class'=>'goods-title']) . Html::tag('p',$attr,['class'=>'goods-attr']);
                $html = Html::tag('div',$img,['class'=>'goods-image']);
                $html .= Html::tag('div',$info,['class'=>'goods-info']);
                echo $html;
                ?>
            </td>
            <td>
                <?php
                  if(empty($goods['productcode'])){
                      echo '--';
                  }else{
                      echo $goods['productcode'];
                  }
                ?>
            </td>
            <td><?=$goods['goods_weight']?></td>
            <td>￥<?=$goods['price']?></td>
            <td>×<?=$goods['quantity']?></td>
            <td>￥<?=$goods['total_price']?></td>
        </tr>

        <?php
        endforeach;
        ?>
        <tr>
            <td class="clearfix" colspan="6">
                <span class="pull-left">买家留言：<?=empty($model->remark)?'无':$model->remark?></span>
                <span class="pull-right">总计金额：￥<?=$model->total_price?> </span>
            </td>
        </tr>
        </tbody>
    </table>
    <!--End 商品信息-->
    <!--收货信息-->
    <fieldset class="layui-elem-field layui-field-title" >
        <legend>收货信息</legend>
    </fieldset>
    <table class="layui-table order-table">
        <colgroup>
            <col width="150">
            <col width="180">
            <col >

        </colgroup>
        <thead>
        <tr>
            <th>收货人</th>
            <th>收货电话</th>
            <th>收货地址</th>

        </tr>
        </thead>
        <tbody>
        <tr>

            <td><?=$model->address->name?></td>
            <td><?=$model->address->phone?></td>
            <td><?=$model->addressText?> </td>
        </tr>
        </tbody>
    </table>
    <!--End 收货信息-->
    <?
    if($model->pay_status ==1):
    ?>
    <!--付款信息-->
    <fieldset class="layui-elem-field layui-field-title" >
        <legend>付款信息</legend>
    </fieldset>
    <table class="layui-table order-table">
        <colgroup>
            <col width="200">
            <col width="110">
            <col >
            <col width="110">
            <col width="170">


        </colgroup>
        <thead>
        <tr>
            <th>应付款金额</th>
            <th>支付方式</th>
            <th>支付流水号</th>
            <th>付款状态</th>
            <th>付款时间</th>

        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?=$model->pay_price?></td>
            <td><?=$model->payTypeText?></td>

            <td><?=$model->transaction_id?></td>
            <td><?=$model->payStatusText?></td>
            <td><?=date('Y-m-d H:i:s',$model->pay_time)?></td>
        </tr>
        </tbody>
    </table>
    <!--End 付款信息-->
    <?
    endif;
    if($model->delivery_status ==1):
    ?>
    <!--发货信息-->
    <fieldset class="layui-elem-field layui-field-title" >
        <legend>发货信息</legend>
    </fieldset>
    <table class="layui-table order-table">
        <colgroup><col >
            <col width="200">
            <col width="110">
            <col width="170">

        </colgroup>
        <thead>
        <tr>
            <th>物流公司</th>
            <th>物流单号</th>
            <th>发货状态</th>
            <th>发货时间</th>

        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?=$model->shipping_company?></td>
            <td><?=$model->shipping_sn?></td>
            <td><?=$model->deliveryStatusText?></td>
            <td><?=date('Y-m-d H:i:s',$model->delivery_time)?></td>

        </tr>
        </tbody>
    </table>
    <!--End 发货信息-->
    <?
    endif;
    ?>
</div>
