<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;
use yii\widgets\ActiveField;

/* @var $this yii\web\View */
/* @var $model backend\models\Links */
/* @var $form yii\widgets\ActiveForm */
$js = <<<JS
function mySubmit(option,_this){  
   if( option === undefined ){            
            $('.ajax-submit').click();
   }else{
       
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
       var form = $('form');              
       var Url   = form.attr('action');
       var Data  = form.serialize();
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
            $model->sort    = 50;
            $model->status  = 1;
        }
        if($model->isNewRecord){
       /* $attr_id = Yii::$app->request->get('attr_id','');
        echo Html::hiddenInput(Html::getInputName($model,'attr_id'),$attr_id);*/

        $hint = '<span style="padding-left: 100px; ">支持批量，多个用英文逗号隔开。如:白色,黑色,蓝色</span>';
        echo $form->field($model, 'value')
            ->textarea(['maxlength' => true,'style'=>'width:600px;height:120px;'])
            ->hint($hint);
        }else{
            echo $form->field($model, 'value')
                ->textInput(['maxlength' => true,'style'=>'width:300px;']);
        }
        echo $form->field($model, 'sort')
            ->textInput(['maxlength' => true,'style'=>'width:150px;'])
            ->hint('数字越小越靠前');
        echo $form->field($model, 'status')->radioList($model->getStatus(),[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                return $return;
            }
        ]);


        $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
        echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ActiveForm::end();
    ?>
