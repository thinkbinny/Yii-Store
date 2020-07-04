<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;
use yii\widgets\ActiveField;
/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs("
function mySubmit(){ 
   $('.ajax-submit').click();
}
",\yii\web\View::POS_END);
$this->registerCss("
.field-model-engine_type .layui-form-select dl{max-height: 190px;}
");
?>


    <?php

    $form = ActiveForm::begin([
        'options'=>['class'=>'layui-form'],

    ]);
        echo $form->field($model, 'engine_type')->dropDownList($model->getEngineType(), ['encode' => false]);
        echo $form->field($model, 'extend')->dropDownList($model->getExtend(),['encode' => false]);
        echo $form->field($model, 'name')->textInput(['maxlength' => true,'placeholder'=>'请填写数据表名'])->width(300);
        echo $form->field($model, 'title')->textInput(['maxlength' => true,'placeholder'=>'请填写模型名称'])->width(300);

        //echo $form->field($model, 'remark')->textInput(['maxlength' => true])->width(500);


        $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
        echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ActiveForm::end();

    ?>
