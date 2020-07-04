<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;
//use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Admin */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs("
function mySubmit(){ 
   $('.ajax-submit').click();
}
",\yii\web\View::POS_END);
?>

<?
$form = ActiveForm::begin([
    'options'=>['class'=>'layui-form'],

]);
    if(empty($model->name)){
        echo $form->field($model, 'name')->textInput()->hint('例如：administors')->width(200);
    }else{
        echo $form->field($model, 'name')->textInput(['readonly'=>'readonly','style'=>'border: none']);
    }
    echo $form->field($model, 'description')->textInput()->hint('例如：超级管理员')->width(200);

    $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
    echo Html::tag('div',$Button,['class'=>'layui-hide']);
ActiveForm::end();
?>



