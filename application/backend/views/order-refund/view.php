<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;
use yii\widgets\DetailView;
use common\components\Func;
/* @var $this yii\web\View */
/* @var $model backend\models\Links */

$this->title = $model->getModelTypeText();
$this->params['breadcrumbs'][] = ['label' => '订单管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;
$js = <<<JS
//verifyType
layui.use('form', function() {
    var form = layui.form;
    form.render();  
    form.on('radio(verifyType)', function (data) {
       
        if(data.value == 1){
            $('.address_id').show();       
        }else{
            $('.address_id').hide();              
        }
    });
});


function mySubmit(){ 
    
     parent.layer.confirm("确认要执行该操作吗？", {icon: 3, title:'提示'}, function(index){
         $('.ajax-submit').click();
        parent.layer.close(index);
        return false;
    });
    return false;
    
  
}
JS;
$this->registerJs($js,\yii\web\View::POS_END);
$js = <<<JS
 $('.operation').selectWindows({
    title:'退款到用户余额',
    btn : ['确定退款', '关闭'],
    area: ['550px', '365px']
 });
JS;
$this->registerJs($js);

$css = <<<CSS
.address_id{display:inline-block;}
.layui-table .goods-detail > div > div{float: left;}
.layui-table .goods-detail .goods {margin-bottom: 5px;}
.layui-table .goods-detail .goods-image{margin-right: 6px;}
.layui-table .goods-detail .goods-image .pic_url {width: 72px;height: 72px;background: no-repeat center center / 100%; }
.layui-table .goods-detail .goods-info{width: 340px;height: 72px;}
.layui-table .goods-detail .goods-info p { display: block;white-space: normal;margin: 0 0 3px 0;padding: 0 5px;text-align: left; }
.layui-table .goods-detail .goods-info p.goods-title {max-height: 40px;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical; -webkit-line-clamp: 2; text-align: left !important;white-space: normal; }
.layui-table .goods-detail .goods-info .goods-attr {border: none;font-size: 12px;color: #7b7b7b }

.operation label{width: 80px;font-weight:400;text-align: right;}
.operation p{margin: 3px 0;}
CSS;
$this->registerCss($css);

?>
<div class="layuimini-container">
    <div class="layuimini-main">
        <?
        $form = ActiveForm::begin([
            'options'=>['class'=>'layui-form'],

        ]);
        ?>
        <fieldset style="margin-top:10 !important;" class="layui-elem-field layui-field-title" >
            <legend ><?=$model->getModelTypeText()?></legend>
        </fieldset>
        <?php
        echo DetailView::widget([
            'model' => $model,
            'options'=>['class'=>'layui-table'],
            'attributes' => [
                /*[
                    'attribute'     =>  'id',
                    'label'         =>  'ID',
                    'captionOptions'=>['style'=>'width:150px;']
                ],*/
                [
                    'captionOptions'=>['style'=>'width:150px;'],
                    'attribute' =>  'order_sn',
                    'label'     =>  '订单号'
                ],
               [
                    'attribute' =>  'uid',
                    'label'     =>  '用户',
                    'value'     =>  function ($model){
                        return Func::get_nickname($model->uid).'【id：'.$model->uid.'】';
                    }
                ],
                [
                    'attribute' =>  'created_at',
                    'label'     =>  '申请日期',
                    'format'    =>  ['date','Y-MM-dd H:i:s'],
                ],
                [
                    'attribute' =>  'order_goods_id',
                    'format'    =>  'raw',
                    'label'     =>  '商品信息',
                    'contentOptions'=>['class'=>'goods-detail'],
                    'value'     =>  function ($model){
                        $volist = $model->getOrderGoodsText();
                        $html   = '';
                        foreach ($volist as $value){
                            $image_url = Func::getImageUrl($value->image_id);
                            $pic_url = Html::tag('div','',['class'=>'pic_url','style'=>"background-image:url('".$image_url."')"]);
                            $title   = Html::tag('p',$value->title,['class'=>'goods-title']);
                            $title   .= Html::tag('p',$value->sku_attr_name,['class'=>'goods-attr']);
                            $goods = Html::tag('div',$pic_url,['class'=>'goods-image']);
                            $goods .= Html::tag('div',$title,['class'=>'goods-info']);
                            $html  .= Html::tag('div',$goods,['class'=>'clearfix goods']);
                        }
                        return $html;
                    }

                ],
                [
                    'attribute' =>  'type',
                    'label'     =>  '服务类型',
                    'value'     =>  function ($model){
                        return $model->getTypeText();
                    }
                ],

                [
                    'attribute' =>  'pay_money',
                    'value'     =>  function ($model){
                        return '￥'.$model->pay_money;
                    }
                ],
                [
                    'attribute' =>  'refund_money',
                    'value'     =>  function ($model){
                        return '￥'.$model->refund_money;
                    }
                ],
                [
                    'attribute' =>  'refund_deposit',
                    'value'     =>  function ($model){
                        return '￥'.$model->refund_money;
                    }
                ],
                [
                    'attribute' =>  'refund_integral_money',
                    'visible'   =>  intval($model->refund_integral_money  !=0 ?1:0 ),
                    'value'     =>  function ($model){
                        return '￥'.$model->refund_integral_money;
                    }
                ],
                [
                    'attribute' =>  'refund_coupon_money',
                    'visible'   =>  intval($model->refund_coupon_money  !=0 ?1:0 ),
                    'value'     =>  function ($model){
                        return '￥'.$model->refund_coupon_money;
                    }
                ],
                [
                    'attribute' =>  'refund_redbags_money',
                    'visible'   =>  intval($model->refund_redbags_money  !=0 ?1:0 ),
                    'value'     =>  function ($model){
                        return '￥'.$model->refund_redbags_money;
                    }
                ],
                [
                    'attribute' =>  'refund_reason',
                    'label'     =>  '退货原因'
                ],
                [
                    'attribute' =>  'refund_explain',
                    'label'     =>  '退货描述'
                ],
                [
                    'attribute' =>  'image',
                    'label'     =>  '用户上传照片',
                    'format'    =>  'raw',
                    'value'     =>  function ($model){
                        $html   = '';
                        $image  = explode(',',$model->image);
                        if(!empty($image)){
                            foreach ($image as $value){
                                if(!empty($value)){
                                    $pic_url = Func::getImageUrl($value);
                                    $html .= Html::img($pic_url);
                                }
                            }
                        }
                        return Html::tag('div',$html);
                    }
                ],
                [
                    'attribute' =>  'operation',
                    'label'     =>  '物流信息',
                    'format'    =>  'raw',
                    'visible'   =>  intval($model->status >=1 && ($model->type == 2 || $model->type == 3) ?1:0 ),
                    'value'     =>  function ($model){
                        if(empty($model->operation)){
                            return '';
                        }else{
                            $data = \yii\helpers\Json::decode($model->operation,true);//$data['name']
                            $html = '';

                            $html .= Html::tag('p','<label>收货人：</label>'.$data['name']);
                            $html .= Html::tag('p','<label>收货号码：</label>'.$data['phone']);
                            $html .= Html::tag('p','<label>收货地址：</label>'.$data['detail']);

                            if(isset($data['express_name'])){
                            $html .= Html::tag('p','<label>快递公司：</label>'.$data['express_name']);
                            $html .= Html::tag('p','<label>快递单号：</label>'.$data['express_sn']);
                            $html .= Html::tag('p','<label>发货时间：</label>'.date('Y-m-d H:i:s',$data['express_time']));
                            }
                            return Html::tag('div',$html,['class'=>'operation']);
                        }
                    },
                ],



                [
                    'attribute' =>  'status',
                    //'label'     =>  '审核意见',
                    'format'    =>  'raw',
                    'value'     =>  function ($model){
                        if( $model->status == 0 ){
                            $dropDownList =  '';
                            if($model->type == 2 || $model->type == 3){
                                $data = $model->getReturnAddress();
                                $addressIdName = Html::getInputName($model,'address_id');
                                $dropDownList = Html::dropDownList($addressIdName,false,$data,['prompt'=>'请选择收货地址']);
                            }
                            $dropDownList = Html::tag('div',$dropDownList,['style'=>'width: 425px;display: none;','class'=>'address_id','id'=>Html::getInputId($model,'address_id')]//display:inline-block
                            );

                            $inputName = Html::getInputName($model,'verify');
                            $return = Html::radioList($inputName,null,['0'=>'拒绝通过','1'=>'审核通过'],['style'=>'display: inline-block',
                                'item' => function($index, $label, $name, $checked, $value)
                                {
                                    $checked=$checked?"checked":"";
                                    $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\" lay-filter=\"verifyType\">";
                                    return $return;
                                }
                            ]);
                            return Html::tag('div',$return . $dropDownList,['id'=>Html::getInputId($model,'verify')]);
                        }else{
                            return $model->getStatusText();
                        }
                    }
                ],
                [
                    'label'     =>  '退款方式',
                    'attribute' =>  'status',
                    'format'    =>  'raw',
                    'visible'   =>  intval(($model->model_type == 2 && $model->type == 2 && $model->status ==2) || ($model->model_type == 1 && $model->status == 0) ?1:0 ),
                    'value'     =>  function($model){
                        $url = \yii\helpers\Url::to(['operation','id'=>$model->id,'type'=>'original']);
                        $html = Html::a('支付原路退回','javascript:void(0);',['data-url'=>$url,'class'=>"btn btn-info btn-xs operation"]);
                        $url = \yii\helpers\Url::to(['operation','id'=>$model->id,'type'=>'platform']);
                        $html .= Html::a('退款到用户余额','javascript:void(0);',['data-url'=>$url,'class'=>"btn btn-info btn-xs operation",'style'=>'margin-left: 10px;']);
                        return $html;
                    }
                ],


                [
                    'attribute' =>  'remark',
                    'label'     =>  '处理备注',
                    'format'    =>  'raw',
                    'value'     =>  function ($model){
                        $inputName = Html::getInputName($model,'remark');
                        return Html::textarea($inputName,$model->remark,['class'=>'layui-textarea']);
                    }
                ],

            ],
        ]);
        $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
        echo Html::tag('div',$Button,['class'=>'layui-hide']);
        ActiveForm::end();
        ?>
    </div>
</div>
