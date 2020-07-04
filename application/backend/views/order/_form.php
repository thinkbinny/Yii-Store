<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;
use yii\widgets\ActiveField;

/* @var $this yii\web\View */
/* @var $model backend\models\Links */
/* @var $form yii\widgets\ActiveForm */
$js = <<<JS

function mySubmit(){ 
   $('.ajax-submit').click();
}
function tongji(){
    var total_price     = $('.total_price').attr('data-val');
    var shipping_price  = $('.shipping_price').val();
    var money           = $('.money').val();
    
    total_price         = parseFloat(total_price);
    shipping_price      = parseFloat(shipping_price);
    money               = parseFloat(money);
    var type            =   $('input:radio:checked').val();    
    if(isNaN(total_price)){
      total_price = 0;
    }
    if(isNaN(shipping_price)){
      shipping_price = 0;
    }
    if(isNaN(money)){
      if(type == 'final'){
        money = total_price;
      }else{  
        money = 0;
      }
    }
    
    
    var heji = Number(total_price) + Number(shipping_price);
    if(type == 'inc'){
        heji = heji + money;
    }else if(type == 'dec'){
        heji = heji - money;
    }else{
        heji = money + shipping_price;
    }   
    heji = heji.toFixed(2)
    
    $('.tongji').html(heji + ' 元');
}
tongji();
JS;

$this->registerJs($js,\yii\web\View::POS_END);
$js = <<<JS
layui.use('form', function () {
   var form = layui.form;
   form.on('radio(UpdateType)', function (data) {
       tongji();
   });
});   
 $('.baoyou').click(function() {
        $('.shipping_price').val('0.00');
        tongji();
 })
JS;

$this->registerJs($js);
 $form = ActiveForm::begin([
        'options'=>['class'=>'layui-form'],

    ]);

            echo $form->field($model, 'pay_price', [
                'template' => '{label}<div style="width:auto;padding: 9px 0px;" class="layui-input-inline total_price" data-val="'.$model->total_price.'">
                        '.$model->total_price.' 元</div>',
            ])->label('订单总额');
            $model->update_type = 'dec';

            echo $form->field($model, 'shipping_price')
                ->textInput(['maxlength' => 5,'style'=>'width:200px;','class'=>'layui-input shipping_price','onchange'=>'tongji();'])
                ->hint("<button class='layui-btn layui-btn-primary baoyou' type='button'>免邮</button>",['style'=>'padding:0 !important;']);

            echo $form->field($model, 'update_type')->radioList($model->getUpdateType(),[
                'item' => function($index, $label, $name, $checked, $value)
                {
                    $checked=$checked?"checked":"";
                    $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\" lay-filter=\"UpdateType\">";
                    return $return;
                }
            ]);
            echo $form->field($model, 'money')
                ->textInput(['maxlength' => 10,'style'=>'width:250px;','class'=>'layui-input money','onchange'=>'tongji();','onkeypress'=>'tongji();']);

            echo $form->field($model, 'pay_price', [
                'template' => '{label}<div style="width:auto;padding: 9px 0px;" class="layui-input-inline tongji">
                                    '.$model->pay_price.' 元</div>',
            ])->label('合计');

        $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
        echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ActiveForm::end();
    ?>
