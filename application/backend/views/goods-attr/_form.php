<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;
use yii\widgets\ActiveField;

/* @var $this yii\web\View */
/* @var $model backend\models\Links */
/* @var $form yii\widgets\ActiveForm */
$modelTypeName = Html::getInputId($model,'model_type');
$js = <<<JS
function mySuccess(option,_this) {
   if(option.params !== null){        
        var type  = option.params.modelType;
        var child = $('.field-{$modelTypeName}').find('input[type=radio]');
         child.each(function(index, item){ 
             if(parseInt(item.value) === type){                
               item.checked = true; 
             }else{
               item.checked = false; 
               item.disabled= true;
             }         
        });         
         layui.use('form', function() {  
            var form = layui.form;
            form.render(); 
        });
            
   }
}
function mySubmit(option,_this){ 
    
   if(option === undefined){
        $('.ajax-submit').click();   
   }else{
        var layerIndex=0,_this;
        _this = $(this);
        if(window.name !== ''){
            try{
                layerIndex = parent.layer.getFrameIndex(window.name);
            }
            catch(err){
            }
        } 
        /////
       var form = $('form');              
       var Url   = form.attr('action');
       var Data  = form.serializeArray();
        ajaxSubmitCallBack(Url,Data,function (ret) {   
            if(ret.status === true){
                parent.layer.msg(ret.message, {icon: 1,time:1000}, function(){
                    if(layerIndex !== 0){
                        parent.layer.close(layerIndex);
                    }
                    option.done(ret);
                });
            }else{
                layer.msg('系统脚本出错', {icon: 5});
                return false;
            }
           
        });
       return false;
        
   } 
  
}
JS;

$this->registerJs($js,\yii\web\View::POS_END);

?>


    <?php $form = ActiveForm::begin([
        'options'=>['class'=>'layui-form'],

    ]);

        if($model->isNewRecord){
            $model->is_required = 0;
            $model->model_type  = 1;
            $model->type        = 3;
            $model->sort        = 50;
        }

        echo $form->field($model, 'name')
            ->textInput(['maxlength' => true,'style'=>'width:200px;'])
            ->hint('商品属性,例如：颜色、衣长、袖长、流行元素等');

        echo $form->field($model, 'model_type')->radioList($model->getModelType(),[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                return $return;
            }
        ]);
        echo $form->field($model, 'is_required')->radioList($model->getIsRequired(),[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                return $return;
            }
        ]);

        echo $form->field($model, 'type')->radioList($model->getType(),[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                return $return;
            }
        ]);

        echo $form->field($model, 'description')
            ->textarea(['maxlength' => true,'style'=>'width:480px;']);
            //
        echo $form->field($model, 'sort')
            ->textInput(['maxlength' => true,'style'=>'width:150px;'])
            ->hint('数字越小越靠前');

        $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
        echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ActiveForm::end();
    ?>
