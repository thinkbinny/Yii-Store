<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs('
    $(".tab-nav li").click(function(){
        $(".tab-nav li").removeClass("current");
        $(this).addClass("current");
        var tab = $(this).attr("data-tab");
        $(".tab-menu").hide();
        $("#"+tab).show();
    });
    $("#Optype").change(function(){
        var val = $(this).val();
        if(val=="") return false;
        $("#field").val($("#field").attr("data-"+val));
    })
');
$this->registerCss('

    .hint-block{color: #aaa; font-weight: normal; margin-left: 8px;display:inline-block;}
');
?>


<div class="attribute-form">
    <?php $form = ActiveForm::begin([
        'fieldConfig'=>[
            'template' => '{label} {hint}{input}{error}'
            ]
        ]); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true,'style'=>'width:350px;'])->hint('（请输入字段名 英文字母开头，长度不超过30）'); ?>
        <?= $form->field($model, 'title')->textInput(['maxlength' => true,'style'=>'width:350px;'])->hint('（请输入字段标题，用于表单显示）');?>
        <?
        $get_attribute_type = $model->get_attribute_type_list();
        $data = array();
        $data2 = array();
        foreach($get_attribute_type as $key=> $val){
            $data[$key]     = $val[0];
            $data2['data-'.$key]    = $val[1];
        }
        $data2['maxlength'] = true;
        $data2['style']     = 'width:350px;';
        $data2['id']        = 'field'; //field
        echo $form->field($model, 'type')->dropDownList($data,['id'=>'Optype','style'=>'width:auto','prompt'=>'请选择'])->hint('（用于表单中的展示方式）'); ?>
        <?= $form->field($model, 'field')->textInput($data2)->hint('（字段属性的sql表示）');?>
        <?= $form->field($model, 'extra')->textarea(['style'=>'height:100px;width:500px'])->hint('（布尔、枚举、多选字段类型的定义数据）') ?>
        <?= $form->field($model, 'value')->textInput(['maxlength' => true,'style'=>'width:350px;'])->hint('（字段的默认值）');?>
        <?= $form->field($model, 'remark')->textInput(['maxlength' => true,'style'=>'width:350px;'])->hint('（用于表单中的提示）');?>
        <?= $form->field($model, 'is_show')->dropDownList($model->get_is_show(),['style'=>'width:auto'])->hint('（是否显示在表单中）');?>
        <?= $form->field($model, 'is_must')->dropDownList($model->get_is_must(),['style'=>'width:auto'])->hint('（用于自动验证）');?>
        <?= $form->field($model, 'validate_type')->dropDownList($model->get_validate_type(),['style'=>'width:auto'])->hint('（验证方式）');?>
        <?= $form->field($model, 'validate_rule')->textInput(['maxlength' => true,'style'=>'width:350px;'])->hint('（根据验证方式定义相关验证规则）');?>
        <?= $form->field($model, 'error_info')->textInput(['maxlength' => true,'style'=>'width:350px;']);?>
        <?= $form->field($model, 'validate_time')->dropDownList($model->get_validate_time(),['style'=>'width:auto']);?>
        <?= $form->field($model, 'auto_type')->dropDownList($model->get_auto_type(),['style'=>'width:auto']);?>
        <?= $form->field($model, 'auto_rule')->textInput(['maxlength' => true,'style'=>'width:350px;'])->hint('（根据完成方式订阅相关规则）');?>
        <?= $form->field($model, 'auto_time')->dropDownList($model->get_validate_time(),['style'=>'width:auto']);?>
        <?= $form->field($model, 'model_id')->hiddenInput()->label(false);?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
