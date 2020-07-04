<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
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


$this->registerCss('
.layui-form-select dl{max-height: 260px;}
');
?>

<div class="menu-form">

    <?php $form = ActiveForm::begin([
        'options'=>['class'=>'layui-form submit-ajax'],// submit-ajax

    ]);

    echo $form->field($model, 'parent_id')
        ->dropDownList([0 => '顶级菜单']+$treeArr, ['encode' => false,'lay-filter'=>'formtype','prompt' => '请选择']);

    echo  $form->field($model, 'name')
        ->textInput(['maxlength' => true])
        ->width(500);

    echo $form->field($model, 'image_id')
        ->widget('extensions\uploads\Uploads',['msg'=>'']);
    if(empty($this->sort)){
        $model->sort = 50;
    }
    echo  $form->field($model, 'sort')
        ->textInput(['maxlength' => true])
        ->width(300);
    $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
    echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ?>

    <?php ActiveForm::end(); ?>

</div>
