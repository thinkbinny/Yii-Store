<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile((new assets\AppAsset)->baseUrl.'/js/vue.min.js', [ 'depends' => 'assets\backendAsset']);
$this->registerJsFile(Yii::$app->params['assetsUrl'] .'/js/delivery.js?t='.rand(0,232331), ['depends' => 'assets\backendAsset']);
$ruleName         = Html::getInputName($model,'rule');
$js = <<<JS
 var rule   =  JSON.parse('{$model->getDeliveryRule()}');
//console.log(rule);
 var appVue = new delivery({
        el: '#deliveryRegion',
        mode: 1,
        regions: JSON.parse('{$model->getRegion()}'),
        cityCount: {$model->getCityCount()},
        formData: rule
    
    });
    function mySubmit(option,_this){  //option === undefined                  
             var layerIndex=0,_this;
            _this = $(this);
            if(window.name !== ''){
                try
                {
                    layerIndex = parent.layer.getFrameIndex(window.name);
                }
                catch(err){
                }
    
            }
           var form = $('form');              
           var Url   = form.attr('action');
           var Data  = form.serializeArray();
           var ruleData = appVue.getData();
           
            if(ruleData.length <= 0){
                layer.tips('请选择配送区域', $('#delivery-region'), {
                     tips: [1, '#e74c3c']
                 });
                 return false;
            }
            for (var i in ruleData){
                var rule = ruleData[i];
                if(rule.first === undefined ){
                    layer.msg('请填写首件/首重', {icon: 5});
                    return false;
                }
                if(rule.first_fee === undefined ){
                    layer.msg('请填写运费金额', {icon: 5});
                    return false;
                }
                if(rule.additional === undefined ){
                    layer.msg('请填写续件数量', {icon: 5});
                    return false;
                }
                if(rule.additional_fee === undefined ){
                    layer.msg('请填写续费金额', {icon: 5});
                    return false;
                }
                
                Data.push({
                    'name':'{$ruleName}['+i+'][province]',
                    'value':rule.province
                    },{
                    'name':'{$ruleName}['+i+'][citys]',
                    'value':rule.citys
                    },{
                    'name':'{$ruleName}['+i+'][first]',
                    'value':rule.first
                    },{
                    'name':'{$ruleName}['+i+'][first_fee]',
                    'value':rule.first_fee
                    },{
                    'name':'{$ruleName}['+i+'][additional]',
                    'value':rule.additional
                    },{
                    'name':'{$ruleName}['+i+'][additional_fee]',
                    'value':rule.additional_fee
                    }); 
               //return false;
            }
            
            
           
            ajaxSubmitCallBack(Url,Data,function (ret) {   
                if(ret.status === true){
                    parent.layer.msg(ret.message, {icon: 1,time:1000}, function(){
                        if(layerIndex !== 0){
                            parent.layer.close(layerIndex);
                        }
                        if(option !== undefined){
                            option.done(ret);
                        }else{
                            parent.window.location.reload();
                        }
                    });
                }else{
                    layer.msg('系统脚本出错', {icon: 5});
                    return false;
                }
               
            });
           return false;
      
    }
   function updateRender() { 
     layui.use('form', function() {
        var form = layui.form;
        form.render('checkbox');
     });
   }
JS;

$this->registerJs($js,\yii\web\View::POS_END);
$js = <<<JS

layui.use('form', function() {
    var form = layui.form;
    form.render();  
  
    
    form.on('radio(formMode)', function (data) {          
        if(data.value == 1){ 
           appVue._data.mode=1;          
        }else{
            appVue._data.mode=2;   
        }
    });
    form.on('checkbox(select-check-all)', function (data) {   
        
        appVue.onCheckAll(data.elem.checked);
    });
    

    form.on('checkbox(onCheckedProvince)', function (data) { 
        //console.log(data.value,data.elem.checked);        
        appVue.onCheckedProvince(data.value,data.elem.checked);
        
    });
    form.on('checkbox(onCheckedCity)', function (data) { 
        var provinceid = data.elem.attributes.provinceid.value;
        //console.log(data.elem.checked);
       appVue.onCheckedCity(data.value,provinceid,data.elem.checked);
    });
});



JS;

$this->registerJs($js);

$css = <<<CSS
    .iframe-popup .layui-form-label{width: 150px !important;}
    .layui-form-select dl{max-height: 260px;}
    .layui-table > tbody > tr:hover{background: none;}
    .layui-table > tbody > tr > th{text-align: center;font-weight: bold;}
    .layui-table > tbody > tr .add-region{border-radius: 2px;font-size: 12px;color:#444;background: #e6e6e6;padding: 5px 10px;vertical-align: middle;display: inline-block}
    .layui-table > tbody > tr .layui-icon-location{font-size: 12px;color:#5eb95e;}
    .layui-table td{padding: 9px 10px !important;}
    .layui-table td p{color: #333;margin-bottom: 0;}
    .layui-table td p span{padding:0 2px;}
    .layui-table td p .grey{color: #7b7b7b}
    .layui-table td .operation{text-align: right;}
    .layui-table td .operation a{color: #0e90d2;margin: 5px;}
    .layui-table td .operation .delete{margin-right: 5px;}
/*地区选择*/
.regional-choice {display: none;user-select: none;}
.regional-body{}

.regional-body .checkbtn{background-color: #fbfbfb;text-align: right;}
.regional-body > div{width: 100%;padding: 10px 30px;}
.regional-body label{font-weight: 400;font-size: 14px;}
.regional-body .checkbtn a{height: 30px;line-height: 30px;display: inline-block;width: 60px;text-align: center;}
.regional-body .place-body > div {width: 170px;margin: 0;padding-bottom: 10px;padding-top: 5px;position: relative;float:left;}
.regional-body .place-body label{padding-right: 10px;text-align: left;width: auto;float: left;cursor: pointer;}
.regional-body .place-body .city-item{width: auto;background-color:#fff;position: absolute;top: 35px;border: 1px solid #ccc;z-index: 100;visibility: hidden;}
.regional-body .place-body .city-item .up-arrow{width: 0;height: 0;border-left: 8px solid transparent; border-right: 8px solid transparent;border-bottom: 10px solid #ccc; position: absolute; top: -10px; left: 20px;}
.regional-body .place-body .city-item > i.up-arrow i { width: 0;height: 0;border-left: 8px solid transparent;border-right: 8px solid transparent;border-bottom: 10px solid #fff;position: absolute;top: 1px;left: -8px; }

.regional-body .place-body .city-item .row-item {min-width: 250px;padding: 10px;box-sizing: border-box; }
.regional-body .place-body .city-item .row-item > p{padding:5px 0;min-width: 110px;}
.regional-body .place-body .city-item .row-item label span { max-width: 175px; white-space: nowrap; vertical-align: middle;font-size: 1.4rem; }

.regional-body .place-body > div:hover .city-item {visibility: visible; }
/*.regional-body .place-body > div .city-item {visibility: visible; }*/
.regional-body .place-body p {float: left;width: auto;margin: 2px 0; }


/*
第四个
*/
.regional-body .place-body > div:nth-child(4n+4) .city-item{right: 0;}
.regional-body .place-body > div:nth-child(4n+4) .city-item .up-arrow{left: 100px;}
CSS;

$this->registerCss($css);




?>

<div class="delivery-form" >

    <?php $form = ActiveForm::begin([
        'options'=>['class'=>'layui-form'],

    ]);
    ?>
   <div  id="deliveryRegion">

    <?php
    if($model->isNewRecord){
        $model->sort = 50;
        $model->mode = 1;
    }
    echo  $form->field($model, 'name')
        ->textInput(['maxlength' => true])
        ->width(300);

    echo $form->field($model, 'mode')->radioList($model->getMode(),[
        'item' => function($index, $label, $name, $checked, $value)
        {
            $checked=$checked?"checked":"";
            $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\" lay-filter=\"formMode\">";
            return $return;
        }
    ]);
    echo  $form->field($model, 'sort')
        ->textInput(['maxlength' => true])
        ->width(150);
    ?>
    <div class="layui-form-item field-delivery-region required">
        <label class="layui-form-label" for="delivery-region">配送区域及运费</label>
        <div id="delivery-region" class="layui-input-inline" style="width: 800px;margin-right:0;">

            <table class="layui-table">
                <tbody>
                <tr>
                    <th width="42%">可配送区域</th>
                    <th width="12%">
                        <span class="first">{{ mode == 1 ? '首件 (个)' : '首重 (Kg)' }}</span>
                    </th>
                    <th width="12%">运费 (元)</th>
                    <th width="12%">
                        <span class="additional">{{ mode == 1 ? '续件 (个)' : '续重 (Kg)' }}</span>
                    </th>
                    <th width="12%">续费 (元)</th>
                </tr>
                <tr v-for="(item, formIndex) in forms">
                    <td >
                        <p >
                            <span v-if="item.citys.length == 374">全国</span>
                            <template v-else v-for="(province, index) in item.treeData">
                                <span>{{ province.name }}</span>
                                <template v-if="!province.isAllCitys">
                                    (<span class="grey">
                                     <template v-for="(city, index) in province.citys">
                                     <span>{{ city.name }}</span><span v-if="(index + 1) < province.citys.length">、</span>
                                     </template>
                                     </span>)
                                </template>
                            </template>
                        </p>
                        <p class="operation">
                            <a class="edit" @click.stop="onEditerForm(formIndex, item)" href="javascript:void(0);">编辑</a>
                            <a class="delete" href="javascript:void(0);" @click.stop="onDeleteForm(formIndex)">删除</a>
                        </p>
                        <input type="hidden" :value="item.citys" required>
                    </td>
                    <td>
                        <input class="layui-input" type="number" :value="item.first" v-model="item.first" required>
                    </td>
                    <td>
                        <input class="layui-input" type="number" :value="item.first_fee" v-model="item.first_fee" required>
                    </td>
                    <td>
                        <input class="layui-input" type="number" :value="item.additional" v-model="item.additional">
                    </td>
                    <td>
                        <input class="layui-input" type="number" :value="item.additional_fee" v-model="item.additional_fee">
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="">
                        <a class="add-region" href="javascript:void(0);" @click.stop="onAddRegionEvent">
                            <i class="layui-icon layui-icon-location"></i>
                            点击添加可配送区域和运费
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>

        </div>

    </div>
    <?php

    $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
    echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ?>

<!-- 地区选择 -->
<div ref="choice" class="regional-choice">
    <div class="regional-body">
        <div>
            <div class="checkbtn">
                <?=Html::checkbox('all', false, [
                    'class'     => 'check-all',
                    'title'     => '全选',
                    ':checked'  => 'checkAll',
                    'lay-skin'  => 'primary',
                    'lay-filter'=> 'select-check-all'
                ]);
                ?>

                <a @click="onCheckAll(false)" class="onCheckAll" href="javascript:void(0);" >清空</a>
            </div>
            <div class="place-body clearfix">

                <div v-for="item in regions" v-if="!isPropertyExist(item.id, disable.treeData) || !disable.treeData[item.id].isAllCitys">
                    <?php
                    echo Html::checkbox('',false,[
                        'class'     => 'province',
                        ':title'    => 'item.name',
                        ':value'    => 'item.id',
                        'lay-skin'  => 'primary',
                        ':checked'  => 'inArray(item.id, checked.province)',
                        'lay-filter' => 'onCheckedProvince'
                    ]);

                    ?>
                    <div class="city-item">
                        <i class="up-arrow"><i></i></i>
                        <div class="row-item clearfix">
                            <p v-for="city in item.city" v-if="!inArray(city.id, disable.citys)">
                                <?php
                                echo Html::checkbox('',false,[
                                    'class'     => 'city',
                                    ':title'    => 'city.name',
                                    ':value'    => 'city.id',
                                    'lay-skin'  => 'primary',
                                    ':checked'  => 'inArray(city.id, checked.citys)',
                                    'lay-filter' => 'onCheckedCity',
                                    ':provinceId'=>'item.id'
                                ]);
                                ?>

                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
    <!--End 地区选择 -->
   </div>
    <?php ActiveForm::end(); ?>
</div>