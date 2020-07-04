<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $form yii\widgets\ActiveForm */
$js = <<<JS
function mySubmit(){ 
   $('.ajax-submit').click();
}

layui.use('form', function() {
    var form = layui.form;
    form.render();  
    form.on('radio(formType)', function (data) {
        var type1 = $('.type1');
        var type2 = $('.type2');
        if(data.value == 1){
            type1.addClass('active') && type2.removeClass('active');            
        }else{
            type1.removeClass('active') && type2.addClass('active');           
        }
    });
    
});
JS;

$this->registerJs($js,\yii\web\View::POS_END);
$typeName = Html::getInputId($model,'type');
$css = <<<CSS
.field-{$typeName} .layui-input-inline{display: block;float:none !important;}

.config-type{display: none;}
.config-type.active{display: block}
CSS;
$this->registerCss($css);



?>

<div  style=" ">

    <?php $form = ActiveForm::begin([
        'options'=>['class'=>'layui-form'],

    ]);

    if($model->isNewRecord) {
        $model->sort    = 50;
        $model->amount  = 1;
        $model->type    = 1;
    }

    echo  $form->field($model, 'name')
        ->textInput(['maxlength' => true])
        ->width(300);

    echo  $form->field($model, 'amount')
        ->textInput(['maxlength' => true])
        ->hint('同一订单，打印的次数')
        ->width(150);
    echo  $form->field($model, 'sort')
        ->textInput(['maxlength' => true])
        ->hint('数字越小越靠前')
        ->width(150);

    echo $form->field($model, 'type')->radioList($model->getType(),[
        'item' => function($index, $label, $name, $checked, $value)
        {
            $checked=$checked?"checked":"";
            $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\" lay-filter=\"formType\">";
            return $return;
        }
    ])->hint('目前支持 飞鹅打印机、365云打印');

    if(!$model->isNewRecord){
        $model->config = \yii\helpers\Json::decode($model->config,true);
    }
    ?>

    <!--飞鹅打印机-->

    <div class="config-type type1 <?=$model->type==1?'active':'';?>">
        <?php
        echo  $form->field($model, 'config[feieyun][user]',['options'=>['class'=>'layui-form-item required']])
            ->textInput(['maxlength' => true,'placeholder'=>'请输入飞鹅云用户名'])
            ->label('飞鹅用户名')
            ->width(250)
            ->hint('飞鹅云后台注册用户名');


        echo  $form->field($model, 'config[feieyun][sn]',['options'=>['class'=>'layui-form-item required']])
            ->textInput(['maxlength' => true,'placeholder'=>'请输入打印机编号'])
            ->label('打印机编号')
            ->width(200)
            ->hint('打印机编号为9位数字，查看飞鹅打印机底部贴纸上面的编号');
        echo  $form->field($model, 'config[feieyun][key]',['options'=>['class'=>'layui-form-item required']])
            ->textInput(['maxlength' => true,'placeholder'=>'请输入打印机秘钥'])
            ->label('打印机秘钥')
            ->width(250)
            ->hint('飞鹅云后台登录生成的UKEY');
        ?>

    </div>
    <!--End 飞鹅打印机-->
    <!--飞鹅打印机-->
    <div class="config-type type2 <?=$model->type==2?'active':'';?>">
    <?php
        echo  $form->field($model, 'config[printcenter][sn]',['options'=>['class'=>'layui-form-item required']])
            ->textInput(['maxlength' => true,'placeholder'=>'请输入打印机编号'])
            ->label('打印机编号')
            ->width(250)
            ->hint('365云打印编号');

        echo  $form->field($model, 'config[printcenter][key]',['options'=>['class'=>'layui-form-item required']])
            ->textInput(['maxlength' => true,'placeholder'=>'请输入打印机秘钥'])
            ->label('打印机秘钥')
            ->width(250)
            ->hint('365云打印机秘钥');
    ?>
    </div>
    <!--End 飞鹅打印机-->

    <?php


    $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
    echo Html::tag('div',$Button,['class'=>'layui-hide']);

     ActiveForm::end();
     ?>
</div>