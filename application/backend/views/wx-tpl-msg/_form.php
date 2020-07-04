<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;
use yii\widgets\ActiveField;

/* @var $this yii\web\View */
/* @var $model backend\models\Links */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs("
layui.use('form', function(){
  var form = layui.form;
  form.render();
 
});
");
$this->registerJs("
function mySubmit(){ 
   $('.ajax-submit').click();
}
",\yii\web\View::POS_END);
?>


<?php $form = ActiveForm::begin([
    'options'=>['class'=>'layui-form'],
    'fieldClass'        =>'backend\widgets\ActiveField',
]);
if(empty($model->status)){
    $readonly = false;
}else{
    $readonly = true;
}

echo $form->field($model, 'title')->textInput(['maxlength' => true,'style'=>'width:350px;'])->hint('（模板使用标题）');

echo $form->field($model, 'template_sn')->textInput(['maxlength' => true,'readonly'=>$readonly,'style'=>'width:350px;'])->hint('（登陆微信后台获取模板编号）');
echo $form->field($model, 'remark')->textarea(['maxlength' => true,'style'=>'width:550px;height:200px;'])->hint('（模板演示或使用方法）');


$Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
echo Html::tag('div',$Button,['class'=>'layui-hide']);
ActiveForm::end();
?>

