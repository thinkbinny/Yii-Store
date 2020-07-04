<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Links */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile((new assets\AppAsset)->baseUrl.'/js/vue.min.js', [ 'depends' => 'assets\backendAsset']);
$this->registerJsFile(Yii::$app->params['assetsUrl'] .'/js/goods-sku.js?t='.rand(0,3333), ['depends' => 'assets\backendAsset']);
$this->registerCssFile(Yii::$app->params['assetsUrl'].'/css/goods-sku.css',[ 'depends' => 'assets\backendAsset']);
$js = <<<JS

function mySubmit(){ 
   $('.ajax-submit').click();
}

JS;

$this->registerJs($js,\yii\web\View::POS_END);
if($model->isNewRecord){
    $mintime     = date('Y-m-d H:i:s');
    $start_time  = date('Y-m-d 00:00:00',strtotime('+1 day'));
    $end_time    = date('Y-m-d 00:00:00',strtotime('+2 day'));
}else{
    $mintime     = date('Y-m-d H:i:s',$model->end_time);
    $start_time  = date('Y-m-d H:i:s',$model->start_time);
    $end_time    = date('Y-m-d H:i:s',$model->end_time);
}

$priceName  = Html::getInputId($model,'price');
$stockName  = Html::getInputId($model,'stock');

$rangedateName      = Html::getInputId($model,'rangedate');
$deliveryTimeName   = Html::getInputId($model,'delivery_time');
$fieldSku           = Html::getInputName($model,'sku');
$goodsdata = [
    'goodsId'   =>  0,
    'title'     =>  '',
    'imageUrl'  =>  '',
    'skuUrl'    =>  '',
    'skuId'     =>  '',
];
if($model->isNewRecord){
    $skuList = 'null';
}else{
    $goodsdata = [
        'goodsId'   =>  $model->goods_id,
        'title'     =>  $goods->title,
        'imageUrl'  =>  $model->getImageUrl($goods->image_id),
        'skuUrl'    =>  '',
        'skuId'     =>  $model->sku_id,
    ];
}
$goodsdata = json_encode($goodsdata);
$js = <<<JS

var skuData = {$skuList};
console.log(skuData);
layui.use('form', function () {
   layui.use('laydate', function(){
         var laydate = layui.laydate;
         laydate.render({
            elem: '#{$rangedateName}'
            ,min: '$mintime'
            ,type: 'datetime'
            ,value: '{$start_time} - {$end_time}'
            ,range: true
            ,change:function(value, date) {
                  value     = value.split(' - ');
              var end_time  = value[1];
              $('#{$deliveryTimeName}').val(end_time);
              console.log(end_time);
            }
          });
         
           //初始赋值
          laydate.render({
            elem: '#{$deliveryTimeName}'
            ,value: '{$end_time}'
            ,type: 'datetime'
            ,isInitValue: true
          });
   });
 });

var goodsdata = {$goodsdata};
var vm = new Vue({
    el: '#goods-form',
    data: goodsdata
});
if(skuData!==null){
    if(skuData.sku_list !== null){
        $('.goods-sku').addClass('active');
    }
}
 // 注册商品多规格组件
var sku = new GoodsSku({
   el: '#many-sku',
   baseData: skuData,
   isSpecLocked: false
});

 $('.ajax-windows-category').selectWindows({
    title:'选择商品'
    ,area: ['1100px', '90%']
    ,done:function(data) {
        //data.skuUrl
      $.get({
        url : data.skuUrl,
        success:function(res) {
          vm.goodsId  = data.goodsId;  
          vm.imageUrl = data.imageUrl;  
          vm.title    = data.title;
          
          if(res.status === true){ 
              vm.skuId    = res.sku_id;
              var sku_data = res.data;                
                sku.update({
                    baseData: sku_data
                });
                $('.goods-sku').addClass('active');
                $('.field-{$priceName}').hide();
               
          }else{
              vm.skuId    = res.sku_id;
              sku.update({
                    baseData: null
                });
              $('.goods-sku').removeClass('active');
              $('.field-{$priceName}').show();
              
          }
        }
      }) 
    }
 });

///提交
$(".layui-submit-goods-ajax").submit(function () {
        
        var layerIndex=0,_this;
        _this = $(this);
        if(window.name !== ''){
            try{
                layerIndex = parent.layer.getFrameIndex(window.name);
            }
            catch(err){
            }
        }
        var Url   = _this.attr('action');
        var Data  = _this.serializeArray();
        
        var sku_id  = $('#sku_id').val();
        
        if( sku_id === '' || sku_id === null ){
            layer.msg('请选择商品');
        }else if( parseInt(sku_id) === 0 ){            
            var isEmpty = sku.appVue.isEmptySkuList();
            if(isEmpty === true){
                layer.msg('商品规格不能为空');
                return false;
            }
            var vuedata     = sku.appVue.getData();
            var sku_list = vuedata.sku_list;
            for(var i in sku_list){ 
                var form    = sku_list[i].form;  
                var sku_id  = sku_list[i].sku_id;
                Data.push({
                        'name':'{$fieldSku}['+sku_id+'][discount_price]',
                        'value':form.discount_price
                    },{
                        'name':'{$fieldSku}['+sku_id+'][stock]',
                        'value':form.stock
                    });
                
            }
           // console.log(Data);
            //开始
             ajax('post',Url,Data,function (ret) {  //console.log(ret);return false;
                 if(ret.status === false){
                    var errors = ret.message;               
                    if( typeof(errors) === 'string' ){                    
                        layer.msg(errors, {icon: 5});
                        return false;
                    }                
                     for(var key in errors){ 
                         var err = errors[key];
                         //console.log(key);
                         if($('#'+key).css("display")==='none'){
                             var name = $('#'+key).parent();
                             layer.tips(err[0], name, {
                                 tips: [1, '#e74c3c']
                                 //tipsMore: true
                             });
                             name.focus();
                         }else{
                             layer.tips(err[0], '#'+key, {
                                 tips: [1, '#e74c3c']
                                 //tipsMore: true
                             });
                             $('#'+key).focus();
                         }
                         return false;
                     }
                     
                 }else{ //成功
                    
                     parent.layer.msg(ret.message, {icon: 1,time:1000}, function(){
                         if(layerIndex !== 0){
                             parent.layer.close(layerIndex);
                         }
                         locationUrl(ret.url);
                     });
                 }
             });
                return false;
            //end
        }    
        
        
        return false;
});
JS;

$this->registerJs($js);

$css = <<<CSS
.iframe-popup .layui-form-label{width: 170px !important;}
.layui-form .goods > div{float: left;}
.layui-form .goods {/*margin-bottom: 5px;*/}
.layui-form .goods .goods-image{margin-right: 6px;}
.layui-form .goods .goods-image .pic_url {width: 72px;height: 72px;background: no-repeat center center / 100%; }
.layui-form .goods .goods-info{width: 350px;height: 72px;}
.layui-form .goods .goods-info p { display: block;white-space: normal;margin: 0 0 3px 0;padding: 0 5px;text-align: left; }
.layui-form .goods .goods-info p.goods-title {max-height: 40px;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical; -webkit-line-clamp: 2; text-align: left !important;white-space: normal; }
.layui-form .goods .goods-info .goods-attr {border: none;font-size: 12px;color: #7b7b7b }

CSS;
$this->registerCss($css);

 $form = ActiveForm::begin([
        'options'=>['class'=>'layui-form layui-submit-goods-ajax'],
         'fieldClass'    =>  'backend\widgets\ActiveField',
         'enableClientScript'=>false,

    ]);
            echo $form->field($model, 'title')
                ->textInput(['maxlength' => 50])
                ->label()
                ->width(500);
            $goodsIdName = Html::getInputId($model,'goods_id');
            $url =  Url::to(['goods/select']);
            if($model->isNewRecord){
            $button = <<<BUTTON
                    <a class="layui-btn layui-btn-sm layui-btn-normal ajax-windows-category" href="javascript:void(0);" data-url="{$url}">
                    <i class="layui-icon layui-icon-search"></i>选择商品</a>
BUTTON;
            }else{
                $button = '';
            }

            $skuIdInput = Html::hiddenInput( Html::getInputName($model,'sku_id') ,$model->sku_id,[':value'=>'skuId','id'=>'sku_id']);
            $html = <<<HTML
                <div class="goods clearfix" >
                   <template v-if="goodsId != 0"> 
                       <div class="goods-image">
                            <div class="pic_url" :style="{backgroundImage:'url(\''+imageUrl+'\')'}"></div> 
                       </div>
                       <div class="goods-info">
                            <p class="goods-title">{{title}}</p>
                            <p >ID:{{goodsId}}</p>
                           
                       </div>
                   </template>
                   <div class=''>
                       {$button}
                   </div>
                </div>
HTML;


            echo $form->field($model, 'goods_id',['template'=>"
            {label}
            <div class='clearfix' id='goods-form'>
            {$skuIdInput}  
            <span >{input}</span>              
               {$html}
            </div>
            "])
                ->hiddenInput(['maxlength' => 50,':value'=>'goodsId']);
            ?>

            <!--规格 active-->
            <div id="many-sku" class="goods-sku goods-sku-many required" style="position: relative;">
                <label class="layui-form-label" style="position: absolute;left: 0;">抢购价钱</label>
                <div class="goods-sku-box clearfix">
                    <!-- 商品多规格sku信息 -->
                    <div v-if="sku_list.length > 0" class="sku-scrollable clearfix">
                        <div class="goods-main-sku ">
                            <!-- sku 批量设置 -->
                            <div class="sku-batch form-inline">
                                <div class="sku-form-group">
                                    <label class="sku-form-label">批量设置</label>
                                </div>

                                <div class="sku-form-group">
                                    <input onkeyup="this.value= this.value.match(/\d+(\.\d{0,2})?/) ? this.value.match(/\d+(\.\d{0,2})?/)[0] : ''" class="layui-input sku-input" type="text" v-model="batchData.discount_price" placeholder="抢购价钱">
                                </div>
                                <div class="sku-form-group">
                                    <button @click="onSubmitBatchData" type="button" class="sku-btn">确定</button>
                                </div>
                            </div>
                            <!-- sku table -->
                            <table class="sku-tabel">
                                <tbody>
                                <tr>
                                    <th v-for="item in sku_attr">{{ item.attr_name }}</th>
                                    <th>商家编码</th>
                                    <th>重量(kg)</th>
                                    <th>销售价</th>
                                    <th>当前库存</th>
                                    <th>抢购价钱</th>
                                </tr>
                                <tr v-for="(item, index) in sku_list">
                                    <td v-for="td in item.rows" class="td-sku-value"
                                        :rowspan="td.rowspan">
                                        {{ td.attr_name_value }}
                                    </td>

                                    <td>
                                        {{item.form.productcode}}
                                    </td>
                                    <td>
                                        {{item.form.goods_weight}}
                                    </td>
                                    <td>
                                        {{item.form.price}}
                                    </td>
                                    <td>
                                        {{item.form.stock}}
                                    </td>
                                    <td>
                                        <input onkeyup="this.value= this.value.match(/\d+(\.\d{0,2})?/) ? this.value.match(/\d+(\.\d{0,2})?/)[0] : ''" type="text" class="ipt-w80 layui-input" v-model="item.form.discount_price"  required>
                                    </td>


                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>
            </div>

            <!--End 规格 -->

            <?php
            echo $form->field($model, 'price')
                ->textInput(['maxlength' => 50,
                    'onkeyup'=>"this.value= this.value.match(/\d+(\.\d{0,2})?/) ? this.value.match(/\d+(\.\d{0,2})?/)[0] : ''"
                    ])
                ->hint('商品抢购价格,单位：元')
                ->width(200);
            echo $form->field($model, 'stock')
                ->textInput([
                        'maxlength' => 50,
                        'onkeyup'=>"value=this.value.replace(/\D/g,'')"
                    ])
                ->hint('此抢购活动最多允许抢购的商品数量')
                ->width(200);
            echo $form->field($model, 'buy_limit')
                ->textInput(['maxlength' => 10,
                    'onkeyup'=>"value=this.value.replace(/\D/g,'')"
                    ])
                ->hint('限时抢购每个人能购买的数量')
                ->width(200);
            echo $form->field($model, 'rangedate')
                ->textInput(['maxlength' => 50,'readonly'=>'readonly','placeholder'=>'请选择抢购时间'])
                ->width(350);

            echo $form->field($model, 'delivery_time')
                ->textInput(['maxlength' => 50,'placeholder'=>'请选择提货时间'])
                //->hint('提取时间')
                ->width(200);
            if($model->isNewRecord){
                $model->sort = 50;
            }
            echo $form->field($model, 'sort')
                ->textInput(['maxlength' => 10,
                    'onkeyup'=>"value=this.value.replace(/\D/g,'')"
                ])
                ->hint('数字越小越靠前')
                ->width(200);
            echo $form->field($model, 'description')
                ->textarea(['maxlength' => 50])
                ->label()
                ->width(500);


        $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
        echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ActiveForm::end();
    ?>
