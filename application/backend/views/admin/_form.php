<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model backend\models\Admin */
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
    echo $form->field($model, 'username')->textInput(['maxlength' => true])->hint('后台管理员用户名')->width(250);
    echo $form->field($model, 'password')->passwordInput(['maxlength' => true])->hint('后台管理员登陆密码')->width(250);
    echo $form->field($model, 'realname')->textInput(['maxlength' => true])->hint('后台管理员真实姓名')->width(250);
    echo $form->field($model, 'email')->textInput(['maxlength' => true])->hint('后台管理员Email')->width(250);

    $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
    echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ActiveForm::end();
    ?>


