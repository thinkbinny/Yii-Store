<?php
use yii\helpers\Html;
use yii\helpers\Url;
use backend\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $form yii\widgets\ActiveForm */



$fieldCouponPrice       = Html::getInputId($model,'price');
$fieldCouponDiscount    = Html::getInputId($model,'discount');
$fieldCouponRangedate   = Html::getInputId($model,'rangedate');
$fieldCouponExpireDay   = Html::getInputId($model,'expire_day');
$minDate                = date('Y-m-d');
$maxDate                = date('Y-m-d',strtotime('+3year'));
$js = <<<JS
function mySubmit(){ 
   $('.ajax-submit').click();
}

layui.use('laydate', function(){
  var laydate = layui.laydate;
      //日期时间范围
      laydate.render({
        elem: '#{$fieldCouponRangedate}'
        ,type: 'date'
        ,range: '~'
        ,eventElem: '.layui-icon-date'
        ,trigger: 'click'
        ,min: '{$newDate}'
        ,max: '2080-10-14'
      });
  });
layui.use('form', function() {
    var form = layui.form;
    form.render();  
    form.on('radio(formType)', function (data) {
        var price = $('.field-{$fieldCouponPrice}');
        var discount = $('.field-{$fieldCouponDiscount}');
        if(data.value == 1){ 
            price.css('display','block') && discount.css('display','none');           
        }else{
            price.css('display','none') && discount.css('display','block');         
        }
    });
    form.on('radio(formExpireType)', function (data) {
        var rangedate   = $('.field-{$fieldCouponRangedate}');
        var day         = $('.field-{$fieldCouponExpireDay}');
        if(data.value == 1){ 
            day.css('display','block') && rangedate.css('display','none');           
        }else{
            day.css('display','none') && rangedate.css('display','block');         
        }
    });
    
});



JS;


$this->registerJs($js,\yii\web\View::POS_END);
$css = <<<CSS
.iframe-popup .layui-form-label{width: 150px !important;}

CSS;
if($model->type==2){
$css .=<<<CSS
.field-{$fieldCouponPrice}{display: none;}
CSS;
}else{
$css .=<<<CSS
.field-{$fieldCouponDiscount}{display: none;}
CSS;

}
if($model->expire_type==2){
    $model->rangedate = date('Y-m-d',$model->start_time).' ~ '.date('Y-m-d',$model->end_time);
    $css .=<<<CSS
.field-{$fieldCouponExpireDay}{display: none;}
CSS;
}else{
    $css .=<<<CSS
.field-{$fieldCouponRangedate}{display: none;}
CSS;

}


$this->registerCss($css);

$js = <<<JS

JS;
$this->registerJs($js);

?>

<div class="clearfix" style="">
    <?php
    $form = ActiveForm::begin([
        'options'       =>  ['class'=>'layui-form'],

    ]);

    if($model->isNewRecord){
       $model->type         = 1;
       $model->expire_type  = 1;
       $model->sort         = 50;
       $model->limit_amount = 0;
       $model->expire_day   = 3;
    }

    echo  $form->field($model, 'name')
        ->textInput(['maxlength' => true])
        ->hint('例如：满100减10')
        ->width(300);
    echo $form->field($model,'type')
        ->radioList($model->getType(),[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\" lay-filter=\"formType\">";
                return $return;
            }
        ]);
    echo  $form->field($model, 'price')
        ->textInput([
            'maxlength' => 10,
            'onkeyup'=>"this.value= this.value.match(/\d+(\.\d{0,2})?/) ? this.value.match(/\d+(\.\d{0,2})?/)[0] : ''"
        ])
        ->width(200);

    echo  $form->field($model, 'discount')
        ->textInput([
            'maxlength' => 3,
            'onkeyup'=>"this.value= this.value.match(/\d+(\.\d{0,1})?/) ? this.value.match(/\d+(\.\d{0,1})?/)[0] : ''"
        ])
        ->hint('折扣率范围0-10，9.5代表9.5折，0代表不折扣')
        ->width(200);

    echo  $form->field($model, 'min_price')
        ->textInput([
            'maxlength' => 10,
            'onkeyup'=>"this.value= this.value.match(/\d+(\.\d{0,2})?/) ? this.value.match(/\d+(\.\d{0,2})?/)[0] : ''"
        ])
        ->hint('必须大于或等于0.01')
        ->width(200);


    echo $form->field($model,'expire_type')
        ->radioList($model->getExpireType(),[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\" lay-filter=\"formExpireType\">";
                return $return;
            }
        ]);

    echo  $form->field($model, 'expire_day')
        ->textInput([
            'maxlength' => 5,
            'onkeyup'=>"value=this.value.replace(/\D/g,'')"
        ])
        ->width(200);





    echo  $form->field($model, 'rangedate',[
            'template' => '{label}<div style="width: 300px;position: relative;" class="layui-input-inline"><i style="cursor: pointer;position: absolute;right: 10px;top: 9px;" class="layui-icon layui-icon-date"></i>{input}{error}</div>{hint}'
    ])
        ->textInput([
            'maxlength' => true,
            'readonly'=>'readonly',
            'placeholder'=>'请选择时间范围',
        ]);


    echo  $form->field($model, 'amount')
        ->textInput([
            'maxlength' => 10,
            'onkeyup'=>"value=this.value.replace(/\D/g,'')"
        ])
        ->width(200);

    echo  $form->field($model, 'limit_amount')
        ->textInput([
            'maxlength' => 10,
            'onkeyup'=>"value=this.value.replace(/\D/g,'')"
        ])
        ->hint('限制领取的优惠券数量，0为不限制')
        ->width(200);
    echo  $form->field($model, 'sort')
        ->textInput([
            'maxlength' => 10,
            'onkeyup'=>"value=this.value.replace(/\D/g,'')"
        ])
        ->width(200);


    $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
    echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ?>

    <?php ActiveForm::end(); ?>

</div>
