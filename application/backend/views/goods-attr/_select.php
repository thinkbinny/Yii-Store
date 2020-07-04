<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '选择属性';
$this->params['breadcrumbs'][] = $this->title;


$js = <<<JS
layui.use('form', function() {
    var form = layui.form;
    form.render();  
    form.on('checkbox(check-select-all)', function (data) {
        var child = $(data.elem).parents('.select-body').find('.attr-body .ids');
        child.each(function(index, item){ 
            if(item.disabled !== true){
                item.checked = data.elem.checked;
            }
        });
        form.render('checkbox');
    });
});

$('.add-attr').selectWindows({
    title:'添加属性',
    area:['650px','500px'],
    params:{modelType:{$model_type}},
    done:function(data) {
        window.location.reload();
    }
});

JS;

$this->registerJs($js);
$js = <<<JS
function mySuccess(option,_this) {
    
    //disabled
    
    if(option.params !== null){        
        var attr = option.params;
        for(index in attr){
            $('#attr'+attr[index].attr_id).find('input').attr('disabled',true);
        }
        layui.use('form', function() {
            var form = layui.form;
            form.render(); 
        });
    }
}


var windowIndex = parent.layer.getFrameIndex(window.name);
function mySubmit(option,_this){  
    var attr = [];
    $("input[name='attr']:checked").each(function (index, item) {   
        attr.push({
         id:item.value,
         name:item.title         
         }) 
    });
   if(attr.length === 0){
        layer.msg('请选择数据', {
          icon: 2,
          time: 1000 //2秒关闭（如果不配置，默认是3秒）
        });
        return false;
    }   
    
    option.done(attr,_this); 
    
    parent.layer.close(windowIndex);
    return false;
 }
JS;
$this->registerJs($js,\yii\web\View::POS_END);
$css = <<<CSS
    body{background:#fff;}
    .select-body{width: 100%;padding: 0px 10px;}
    .checkbtn{background-color: #fbfbfb;text-align: right;}
    .checkbtn a{height: 30px;line-height: 30px;display: inline-block;width: 60px;text-align: center;}
    .attr-body{padding:10px 10px;}
    .attr-body > div {width: 145px;margin: 0;padding-bottom: 10px;padding-top: 5px;position: relative;float:left;}
    .regional-body .attr-body label{padding-right: 10px;text-align: left;width: auto;float: left;cursor: pointer;}
    .add-attr{border:1px solid transparent;}
    .add-attr { color: #2589ff;background-color: #fff;border-color: #2589ff;font-size: 12px;border-radius: 4px;outline: 0;font-weight: 400;padding:2px 5px;outline: none !important; }
    .add-attr:hover{background:#2589ff;color: #fff; }
CSS;

$this->registerCss($css);
$form = ActiveForm::begin([
    'fieldClass'    =>  'backend\widgets\ActiveField',
    'action'        => ['select'],
    'method'        => 'get',
    'options'       => ['class'=>'layui-form'],
    'enableClientScript'=>false,
]);
?>
<div class="layuimini-container" style="border:none;">
    <div class="select-body">
        <div class="checkbtn">
            <?=Html::checkbox('all', false, [
                'title'     => '全选',
                'lay-skin'  => 'primary',
                'lay-filter'=> 'check-select-all'
            ]);
            ?>
            <!--<a style="color: #1E9FFF;" href="javascript:void(0);" >添加属性</a>-->
            <button style="margin-left: 10px;" type="button" data-url="<?=Url::to(['create'])?>" class="add-attr">添加属性</button>
        </div>

        <div class="attr-body clearfix">
            <?php
            //$volist = $dataProvider->getModels();
            $html   = '';
            foreach ($volist as $vo){
                $checkbox =  Html::checkbox('attr',false,[
                    'title'     => $vo->name,
                    'value'     => $vo->id,
                    'class'     => 'ids',
                    'lay-skin'  => 'primary',
                ]);
                $html .= Html::tag('div',$checkbox,['id'=>'attr'.$vo->id]);
            }
            echo $html;
            ?>
            <div class="">


            </div>
        </div>

    </div>
</div>
<?php
ActiveForm::end();
?>
