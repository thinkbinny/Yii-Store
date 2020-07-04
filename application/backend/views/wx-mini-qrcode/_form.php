<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;
use yii\widgets\ActiveField;

/* @var $this yii\web\View */
/* @var $model backend\models\Links */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs("
layui.use('colorpicker', function(){
    var colorpicker = layui.colorpicker; 
    //RGB 
      colorpicker.render({
        elem: '#selectColor'
        ,color: '#1E9FFF'
        ,format: 'rgb' //默认为 hex
        ,change: function(color){
          console.log(color);
          var str = color.substr(0,color.length-1);          
          str = str.substr(4);          
                
          $('#line_color').val(str);
          return false;
        }
      });
});
");
$this->registerJs("
function mySubmit(){ 
   $('.ajax-submit').click();
}
",\yii\web\View::POS_END);

$css = <<<CSS
    .selectColor .layui-colorpicker{width: 38px;height: 38px;}
    .layui-colorpicker-main .layui-colorpicker-main-input div.layui-inline{margin-right:0;}
CSS;

$this->registerCss($css);
?>



<?php $form = ActiveForm::begin([
    'options'=>['class'=>'layui-form'],
]);

echo $form->field($model, 'scene')->textInput(['maxlength' => true,'style'=>'width:250px;','placeholder'=>'用于区分扫码来源'])->hint('字符串最大长度为32位');
echo $form->field($model, 'page')->textInput(['maxlength' => true,'style'=>'width:250px;'])->hint('例如 pages/index/index');
echo $form->field($model, 'width')->textInput(['maxlength' => true,'style'=>'width:100px;'])->hint('二维码的宽度，单位 px，最小 280px，最大 1280px');
echo $form->field($model, 'auto_color')->dropDownList(['false'=>'自动','true'=>'黑色']);


$material_button =  Html::tag('div','',['id'=>'selectColor','class'=>'selectColor']);
echo $form->field($model, 'line_color',['options'=>[
    'class'=>'layui-form-item'],
    'template'=>"{label}\n<div class=\"layui-input-inline\" style='width: 300px;'><div style='width: auto;' class=\"layui-input-inline\">{input}</div>".$material_button.'</div>'])
->textInput(['id'=>'line_color']);



echo $form->field($model, 'is_hyaline')->dropDownList(['false'=>'否','true'=>'是']);

$Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
echo Html::tag('div',$Button,['class'=>'layui-hide']);
ActiveForm::end();
?>

