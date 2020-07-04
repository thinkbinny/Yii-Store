<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Config */
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
    if($model->isNewRecord){
        $model->status = 1;
    }
    echo $form->field($model, 'title')->textInput(['maxlength' => true])->width(250);
    echo $form->field($model, 'keyid')->textInput(['maxlength' => true])->width(250);
    echo $form->field($model, 'status')->radioList($model->getStatus(),[
        'item' => function($index, $label, $name, $checked, $value)
        {
            $checked=$checked?"checked":"";
            $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
            return $return;
        }
    ]);
    $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
    echo Html::tag('div',$Button,['class'=>'layui-form-button layui-hide']);
    ActiveForm::end();
    ?>

