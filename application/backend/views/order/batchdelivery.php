<?php
use yii\helpers\Html;
//use backend\widgets\ActiveForm;
use yii\widgets\ActiveForm;



/* @var $this yii\web\View */
/* @var $model backend\models\Links */

$this->title = '批量发货';
$this->params['breadcrumbs'][] = ['label' => '订单管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;
$js = <<<JS

function mySubmit(){ 
   $('.ajax-submit').click();
}

JS;

$this->registerJs($js,\yii\web\View::POS_END);
$file_id = Html::getInputId($model,'format_template');
$js = <<<JS
    $('#{$file_id}').on('change', function () {
        
        var fileNames = '';
        $.each(this.files, function () {            
            fileNames += this.name;
        });
        //$(this).parent().find('input[type=hidden]').val(fileNames);
        $('.file-list').html(fileNames);
    });

    $('form').submit(function(){
        var layerIndex=0,_this;
        _this = $(this);
        if(window.name !== ''){
            try
            {
                layerIndex = parent.layer.getFrameIndex(window.name);
            }
            catch(err)
            {
            }

        }
      
        var Url   = _this.attr('action');
        var formData = new FormData($("#uploadForm")[0]); //此处id为form表单的id
        $.ajax({
            url: Url,
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (ret) {
               
                if(ret.status === false){
                    var errors = ret.message;
                    if( typeof(errors) === 'string' ){
                        layer.msg(errors, {icon: 5});
                        return false;
                    }
                    for(var key in errors){
                        var err = errors[key];
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
                   //console.log('okokokok');return false;
                   parent.layer.msg(ret.message, {icon: 1,time:1000}, function(){
                        if(layerIndex !== 0){
                            parent.layer.close(layerIndex);
                        }
                        locationUrl(ret.url);
                    });
                }
            },
            error: function (data) {
                layer.msg('系统脚本出错', {icon: 5});
                return false;
            }
        });
        
        
        
        //var Data = new FormData();
        //Data.append("files", $("#{$file_id}")[0].files[0]);
        //console.log(Data);
        //return false;
       

       /* ajaxSubmitCallBack(Url,formData,function(ret) {
            console.log(ret);
        })*/
        return false;
        
    });
JS;
$this->registerJs($js);

$css = <<<CSS
.layui-form-select dl{max-height:220px !important;}
.iframe-popup .layui-form-label{width:120px !important;}
.template{position: absolute;
left: 0;top: 0;z-index:1;cursor:pointer;
line-height:30px;opacity:0;/**/
}
CSS;
$this->registerCss($css);
$form = ActiveForm::begin([
    'fieldClass'=>'backend\widgets\ActiveField',
    'id'=>'uploadForm',
    'enableClientScript'=>false,
    'options'=>['class'=>'layui-form'],

]);
$text = '可在 '.Html::a('物流公司列表',['logistics/index'],['style'=>'color:#0e90d2','target'=>'_blank']).' 中设置';
echo $form->field($model, 'shipping_code')
    ->dropDownList($model->shippingCompanyText,['encode' => false,'prompt' => '请选择'])
    ->width(280)
    ->hint($text);

$text = Html::a('默认模板格式下载',['batchdelivery','type'=>'deliverytpl'],['style'=>'color:#0e90d2','target'=>'_blank']);
echo $form->field($model, 'format_template',[
    //'options'=>['class'=>'layui-form-item required'],
    'template'=>
    '{label}<div style="width:280px;position: relative;padding-top: 3px; " class="layui-input-inline">{input}<button class="layui-btn layui-btn-normal layui-btn-sm" type="button"><i class="layui-icon layui-icon-upload"></i>选择模板</button><div class="file-list" style="line-height: 30px;" ></div>{hint}</div>'
])
    ->fileInput(['class'=>'template',])
    ->label('导入发货模板')
    ->hint($text);
//

$Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
echo Html::tag('div',$Button,['class'=>'layui-hide']);
ActiveForm::end();
?>


