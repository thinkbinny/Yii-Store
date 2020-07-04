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
]);

echo $form->field($model, 'valid_days')->textInput(['maxlength' => true,'style'=>'width:250px;'])->hint('天，0表示永久');

echo $form->field($model, 'scene')->textInput(['maxlength' => true,'style'=>'width:150px;','placeholder'=>'用于区分扫码来源'])->hint('数字最大长度为32位,字符串最大长度为64位');
echo $form->field($model, 'callback')->textInput(['maxlength' => true,'style'=>'width:150px;'])->hint('微信板块（behavior\scan）目录下文件');
echo $form->field($model, 'title')->textInput(['maxlength' => true,'style'=>'width:380px;','placeholder'=>'选填']);


$Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
echo Html::tag('div',$Button,['class'=>'layui-hide']);
ActiveForm::end();
?>

