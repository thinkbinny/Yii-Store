<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\GoodsImage;
use backend\models\GoodsDetail;
/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile((new assets\AppAsset)->baseUrl.'/js/vue.min.js', [ 'depends' => 'assets\backendAsset']);
$this->registerJsFile(Yii::$app->params['assetsUrl'] .'/js/goods-sku.js', ['depends' => 'assets\backendAsset']);
$this->registerCssFile(Yii::$app->params['assetsUrl'].'/css/goods-sku.css',[ 'depends' => 'assets\backendAsset']);

if(isset($model->id)){
    $skuList = \backend\models\GoodsSku::getSkuList($model->id);
    $model->sku = $skuList['sku'];
    if($skuList['sku_attr'] == null || $model->sku_type==1){
        $skuList = 'null';
    }else{
        $skuList = json_encode([
            'sku_attr'=>$skuList['sku_attr'],
            'sku_list'=>$skuList['sku_list'],
        ]);
    }
}else{
    $skuList = 'null';
}
$js = <<<JS
function mySubmit(){ 
   $('.ajax-submit').click();
}


layui.use('form', function() {
    var form = layui.form;
    form.render();  
    form.on('radio(formSkuType)', function (data) {
        var single = $('.goods-sku-single');
        var many = $('.goods-sku-many');
        if(data.value == 1){
            single.addClass('active') && many.removeClass('active');            
        }else{
            single.removeClass('active') && many.addClass('active');           
        }
    });
    
});
var skuData = {$skuList};
//console.log(skuData);

 // 注册商品多规格组件
var sku = new GoodsSku({
    el: '#many-sku',
     baseData: skuData,
     isSpecLocked: false
   });


JS;


$this->registerJs($js,\yii\web\View::POS_END);
$css = <<<CSS
.iframe-popup .layui-form-label{width: 170px !important;}
CSS;
$this->registerCss($css);
$fieldSkuType       = Html::getInputName($model,'sku_type');
$fieldSkuPrice      = Html::getInputId($model,'sku[price]');
$fieldSkuStockNum   = Html::getInputId($model,'sku[stock_num]');
$fieldSkuGoodsWeight= Html::getInputId($model,'sku[goods_weight]');
$fieldSkuMany       = Html::getInputName($model,'sku_many');
$js = <<<JS
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
        
        var skuType = $('input:radio[name="{$fieldSkuType}"]:checked').val();
        if(skuType === '2'){
            var isEmpty = sku.appVue.isEmptySkuList();
            if(isEmpty === true){
                layer.msg('商品规格不能为空');
                return false;
            }
            //添加多SKU
            var skuData = sku.appVue.getData();
            //属性
            var sku_attr = skuData.sku_attr;           
            for (var i in sku_attr) {  
                Data.push({
                    'name':'{$fieldSkuMany}[attr]['+i+'][attr_id]',
                    'value':sku_attr[i].attr_id
                },{
                    'name':'{$fieldSkuMany}[attr]['+i+'][attr_name]',
                    'value':sku_attr[i].attr_name
                });           
                //属性值
                var attr_items = sku_attr[i].attr_items;
                for (var k in attr_items) {
                    Data.push({
                    'name':'{$fieldSkuMany}[attr]['+i+'][attr_items]['+k+'][attr_value_id]',
                    'value':attr_items[k].attr_value_id
                    },{
                    'name':'{$fieldSkuMany}[attr]['+i+'][attr_items]['+k+'][attr_name_value]',
                    'value':attr_items[k].attr_name_value
                    }); 
                }
            }
            //SKU数组
            var sku_list = skuData.sku_list;
            for(var i in sku_list){ 
              
                Data.push({
                    'name':'{$fieldSkuMany}[data]['+i+'][sku_attr_id]',
                    'value':sku_list[i].sku_attr_id
                });
                //SKU参数
                var form = sku_list[i].form;                
                    Data.push({
                    'name':'{$fieldSkuMany}[data]['+i+'][form][goods_weight]',
                    'value':form.goods_weight
                    },{
                    'name':'{$fieldSkuMany}[data]['+i+'][form][image_id]',
                    'value':form.image_id
                    },{
                    'name':'{$fieldSkuMany}[data]['+i+'][form][line_price]',
                    'value':form.line_price
                    },{
                    'name':'{$fieldSkuMany}[data]['+i+'][form][price]',
                    'value':form.price
                    },{
                    'name':'{$fieldSkuMany}[data]['+i+'][form][productcode]',
                    'value':form.productcode
                    },{
                    'name':'{$fieldSkuMany}[data]['+i+'][form][stock_num]',
                    'value':form.stock_num
                    }); 
               
            }
            /*console.log(Data);
            return false;*/
            
        }else{
            var SkuPrice        = $('#{$fieldSkuPrice}').val();
            var SkuStockNum     = $('#{$fieldSkuStockNum}').val();
            var SkuGoodsWeight  = $('#{$fieldSkuGoodsWeight}').val();
            if(SkuPrice === null || SkuPrice === ''){               
                var name = $('#{$fieldSkuPrice}').parent();
                 layer.tips('商品价格不能为空', name, {
                     tips: [1, '#e74c3c']
                 });
                 name.focus();
                return false;
            }else if(SkuStockNum === null || SkuStockNum === ''){                
                var name = $('#{$fieldSkuStockNum}').parent();
                 layer.tips('库存数量不能为空', name, {
                     tips: [1, '#e74c3c']
                 });
                 name.focus();
                return false;
            }else if(SkuGoodsWeight === null || SkuGoodsWeight === ''){               
                var name = $('#{$fieldSkuGoodsWeight}').parent();
                 layer.tips('商品重量不能为空', name, {
                     tips: [1, '#e74c3c']
                 });
                 name.focus();
                return false;
            }
             
        }

        
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
    });

$('.ajax-windows-category').selectWindows({
    title:'添加商品分类',    
    area: ['700px', '420px'],
    done: function (data) {
        window.location.reload();
    }

});
$('.ajax-windows-delivery').selectWindows({
    title:'添加运费模板',    
    area: ['1000px', '90%'],
    scrollbar:'Yes',
    done: function (data) {
        window.location.reload();
    }
})
JS;
$this->registerJs($js);

?>

<div class="goods-form" style="">
    <?php
    $form = ActiveForm::begin([
        'options'       =>  ['class'=>'layui-form layui-submit-goods-ajax'],
        'fieldClass'    =>  'backend\widgets\ActiveField',
        'enableClientScript'=>false,
    ]);
    ?>
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>基本信息</legend>
    </fieldset>
    <?php
    echo  $form->field($model, 'title')
        ->textInput(['maxlength' => true])
        ->width(850);
    $addCategoryId = Html::a('<i class="layui-icon layui-icon-addition"></i>添加分类',
        'javascript:void(0);',
        [
            'data-url'=>Url::toRoute(['goods-category/create']),
            'class'=>'layui-btn layui-btn-sm layui-btn-normal ajax-windows-category',
        ]);
    echo $form->field($model, 'category_id')
        ->dropDownList($model->getCategoryId(), ['encode' => false,'prompt' => '请选择'])
        ->hint($addCategoryId,['style'=>'padding:3px 0 0 0 !important']);
    if(isset($model->id)){
    $model->image_id = GoodsImage::getImageId($model->id);
    }
    echo $form->field($model, 'image_id')
        ->widget('extensions\uploads\Uploads',[
            'type'=>'checkbox',
            'msg'=>'尺寸750x750像素以上，大小2M以下 (可拖拽图片调整显示顺序 )']);
    echo $form->field($model,'sellpoint')
        ->textarea(['maxlength' => true])
        ->hint('<span style="padding-left: 170px;">选填，商品卖点简述，例如：此款商品美观大方 性价比较高 不容错过</span>')
        ->width(850);

    ?>
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>
            销售/库存
        </legend>
    </fieldset>
    <?php

    if($model->deduct_stock_type == null){
        $model->deduct_stock_type = 2;
    }
    echo $form->field($model, 'deduct_stock_type')->radioList($model->getDeductStockType(),[
        'item' => function($index, $label, $name, $checked, $value)
        {
            $checked=$checked?"checked":"";
            $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
            return $return;
        }
    ]);

    ?>
    <?php
    if(empty($model->sku_type)){
        $model->sku_type = 1;
    }
    echo $form->field($model, 'sku_type',['options'=>['style'=>'margin-bottom:10px;']])->radioList($model->getSkuType(),[
        'item' => function($index, $label, $name, $checked, $value)
        {
            $checked=$checked?"checked":"";
            $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\" lay-filter=\"formSkuType\">";
            return $return;
        }
    ]);

    ?>
    <!--单规则-->
        <div class="goods-sku goods-sku-single <?=$model->sku_type==1?'active':'';?>">
            <?php
                // 商品单规格
            echo  $form->field($model, 'sku[productcode]')
                ->textInput(['maxlength' => true,'placeholder'=>'请输入商品编码'])
                ->label('商品编码')
                ->width(500);
            //onkeyup= "if(! /^d+$/.test(this.value)){alert('只能整数');this.value='';}"
            echo  $form->field($model, 'sku[price]',['options'=>['class'=>'layui-form-item required']])
                ->textInput([
                    'maxlength' => true,
                    'placeholder'=>'请输入商品价格',
                    'onkeyup'=>"this.value= this.value.match(/\d+(\.\d{0,2})?/) ? this.value.match(/\d+(\.\d{0,2})?/)[0] : ''"
                    ])
                ->label('商品价格')
                ->width(500);

            echo  $form->field($model, 'sku[line_price]')
                ->textInput([
                    'maxlength' => true,
                    'placeholder'=>'请输入商品划线价',
                    'onkeyup'=>"this.value= this.value.match(/\d+(\.\d{0,2})?/) ? this.value.match(/\d+(\.\d{0,2})?/)[0] : ''"
                    ])
                ->label('商品划线价')
                ->width(500);
            echo  $form->field($model, 'sku[stock_num]',['options'=>['class'=>'layui-form-item required']])
                ->textInput([
                    'maxlength' => true,
                    'placeholder'=>'请输入库存数量',
                    'onkeyup'=>"value=this.value.replace(/\D/g,'')"
                    ])
                ->label('库存数量')
                ->width(500);
            echo  $form->field($model, 'sku[goods_weight]',['options'=>['class'=>'layui-form-item required']])
                ->textInput([
                    'maxlength' => true,
                    'placeholder'=>'请输入商品重量(Kg)',
                    'onkeyup'=>"this.value= this.value.match(/\d+(\.\d{0,2})?/) ? this.value.match(/\d+(\.\d{0,2})?/)[0] : ''"
                    ])
                ->label('商品重量(Kg) ')
                ->width(500);
            ?>
        </div>
    <!--多规则-->
        <div id="many-sku" class="goods-sku goods-sku-many <?=$model->sku_type==2?'active':'';?>">
            <div class="goods-sku-box clearfix">

                <!-- sku属性 -->
                <div class="sku-attr clearfix">
                    <div v-for="(item, index) in sku_attr" class="sku-group-item clearfix">
                        <div class="sku-group-name">
                            <span>{{ item.attr_name }}</span>
                            <i v-if="!isSpecLocked" @click="onDeleteGroup(index)"
                               class="sku-group-delete layui-icon layui-icon-close-fill" title="点击删除"></i>
                        </div>
                        <div class="sku-list pull-left">
                            <div v-for="(val, i) in item.attr_items" class="sku-item pull-left">
                                <span>{{ val.attr_name_value }}</span>
                                <i v-if="!isSpecLocked" @click="onDeleteValue(index, i)" class="sku-item-delete layui-icon layui-icon-close-fill" title="点击删除"></i>
                            </div>
                            <div v-if="!isSpecLocked" class="sku-item-add pull-left">
                                <button data-url="<?=Url::toRoute(['goods-attr-value/select'])?>" data-attribute="attr_id" v-bind:data-attribute-value="item.attr_id" v-bind:data-index="index" type="button" class="sku-btn add-sku-attr-value pull-left">添加属性</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 添加规格组：按钮 -->
                <div v-if="!isSpecLocked" class="sku-group-button clearfix">
                    <button type="button" data-url="<?=Url::toRoute(['goods-attr/select'])?>" class="sku-btn add-sku-group">添加规格</button>
                </div>


                <!-- 商品多规格sku信息 -->
                <div v-if="sku_list.length > 0" class="sku-scrollable clearfix">
                    <div class="goods-main-sku ">
                    <!-- 分割线 -->
                    <div class="sku-line"></div>
                    <!-- sku 批量设置 -->
                    <div class="sku-batch form-inline">
                        <div class="sku-form-group">
                            <label class="sku-form-label">批量设置</label>
                        </div>
                        <div class="sku-form-group">
                            <input class="layui-input sku-input" type="text" v-model="batchData.productcode" placeholder="商家编码">
                        </div>
                        <div class="sku-form-group">
                            <input class="layui-input sku-input" type="text" v-model="batchData.price" placeholder="销售价">
                        </div>
                        <div class="sku-form-group">
                            <input class="layui-input sku-input" type="text" v-model="batchData.line_price" placeholder="划线价">
                        </div>
                        <div class="sku-form-group">
                            <input class="layui-input sku-input" type="text" v-model="batchData.stock_num" placeholder="库存数量">
                        </div>
                        <div class="sku-form-group">
                            <input class="layui-input sku-input" type="text" v-model="batchData.goods_weight" placeholder="重量">
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
                            <th>规格图片</th>
                            <th>商家编码</th>
                            <th>销售价</th>
                            <th>划线价</th>
                            <th>库存</th>
                            <th>重量(kg)</th>
                        </tr>
                        <tr v-for="(item, index) in sku_list">
                            <td v-for="td in item.rows" class="td-sku-value"
                                :rowspan="td.rowspan">
                                {{ td.attr_name_value }}
                            </td>
                            <td class="sku-image">
                                <div v-if="item.form.image_id" class="data-image selectImg" data-url="<?=Url::toRoute(['files/browsefile'])?>" v-bind:data-index="index">
                                    <img :src="item.form.image_path" alt="">
                                    <i class="layui-icon layui-icon-close image-delete" @click.stop="onDeleteSkuImage(index)"></i>
                                </div>
                                <div v-else class="upload-image selectImg" data-url="<?=Url::toRoute(['files/browsefile'])?>" v-bind:data-index="index">
                                    <i class="layui-icon layui-icon-add-1"></i>
                                </div>
                            </td>
                            <td>
                                <input type="text" class="ipt-goods-no layui-input" v-model="item.form.productcode">
                            </td>
                            <td>
                                <input onkeyup="this.value= this.value.match(/\d+(\.\d{0,2})?/) ? this.value.match(/\d+(\.\d{0,2})?/)[0] : ''" type="text" class="ipt-w80 layui-input" v-model="item.form.price"  required>
                            </td>
                            <td>
                                <input onkeyup="this.value= this.value.match(/\d+(\.\d{0,2})?/) ? this.value.match(/\d+(\.\d{0,2})?/)[0] : ''" type="text" class="ipt-w80 layui-input" v-model="item.form.line_price">
                            </td>
                            <td>
                                <input onkeyup="value=this.value.replace(/\D/g,'')" type="text" class="ipt-w80 layui-input" v-model="item.form.stock_num" required>
                            </td>
                            <td>
                                <input onkeyup="this.value= this.value.match(/\d+(\.\d{0,2})?/) ? this.value.match(/\d+(\.\d{0,2})?/)[0] : ''" type="text" class="ipt-w80 layui-input" v-model="item.form.goods_weight" required>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    </div>
                </div>


            </div>
        </div>


    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>
            商品详情
        </legend>
    </fieldset>

    <?php
    if(isset($model->id)){
    $model->content = GoodsDetail::getContentText($model->id);
    }
    echo $form->field($model, 'content')
        ->widget('extensions\umeditor\Umeditor',[])
        ->label('商品详情')
        ->width(850);
    ?>
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>
            其他设置
        </legend>
    </fieldset>
    <?php

    $addDeliveryId = Html::a('<i class="layui-icon layui-icon-addition"></i>添加模板',
        'javascript:void(0);',
        [
            'data-url'=>Url::toRoute(['delivery/create']),
            'class'=>'layui-btn layui-btn-sm layui-btn-normal ajax-windows-delivery',
        ]);

    echo $form->field($model,'delivery_id')
        ->dropDownList($model->getDeliveryId(),['prompt' => '请选择'])
        ->hint($addDeliveryId,['style'=>'padding:3px 0 0 0 !important']);


    if($model->status == null){
        $model->status = 1;
    }
    echo $form->field($model,'status')
        ->radioList($model->getStatus(),[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                return $return;
            }
        ]);

    echo  $form->field($model, 'sales_initial')
        ->textInput(['maxlength' => true])
        ->width(300);



    if(empty($this->sort)){
        $model->sort = 50;
    }
    echo  $form->field($model, 'sort')
        ->textInput(['maxlength' => true])
        ->width(300);

    ?>
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>
            积分设置
        </legend>
    </fieldset>
    <?php

    if($model->isNewRecord){
        $model->is_points_gift = 1;
    }
    echo $form->field($model,'is_points_gift')
        ->radioList($model->getIsPointsGift(),[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                return $return;
            }
        ]);
    if($model->isNewRecord){
        $model->is_points_discount = 1;
    }
    echo $form->field($model,'is_points_discount')
        ->radioList($model->getIsPointsDiscount(),[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                return $return;
            }
        ])
        ->hint('注：如需使用积分功能必须在 [营销管理 - 积分设置] 中开启')
        ->width(850);
    ?>
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>
            会员折扣设置
        </legend>
    </fieldset>
    <?php
    if($model->isNewRecord){
        $model->is_vip = 1;
    }
    echo $form->field($model,'is_vip')
        ->radioList($model->getIsVip(),[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                return $return;
            }
        ]);
    ?>
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>
            分销设置
        </legend>
    </fieldset>
    <?php
    if($model->isNewRecord){
        $model->is_fenxiao = 0;
    }
    echo $form->field($model,'is_fenxiao')
        ->radioList($model->getIsFenxiao(),[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                return $return;
            }
        ]);
    ?>
    <?php
    $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
    echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ?>

    <?php ActiveForm::end(); ?>

</div>
