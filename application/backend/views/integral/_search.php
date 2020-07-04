<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\search\MenuSearch */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs("
layui.use('form', function() {
    var form = layui.form;
    form.render();  
    
    
});
");

?>
<div class="page-toolbar clearfix">
    <div class="layui-btn-group ">




    </div>
    <div class="page-filter pull-right layui-search-form">

        <?php

        $form = ActiveForm::begin([
            'fieldClass' => 'backend\widgets\ActiveSearch',
            'action' => ['index'],
            'method' => 'get',
            'options'=>['class'=>'layui-form'],
        ]);
        echo $form->field($model, 'type',['options'=>['class'=>'layui-input-inline','style'=>'width: 100px;']])
            ->dropDownList($model->getType(),['prompt' => '操作类型'])->label(false);
        echo $form->field($model, 'status',['options'=>['class'=>'layui-input-inline','style'=>'width: 100px;']])
            ->dropDownList($model->getStatus(),['prompt' => '积分状态'])->label(false);

        echo $form->field($model, 'rangedate',['options'=>['class'=>'layui-input-inline','style'=>'width: 250px;']])
            ->textInput(['placeholder' => '请输入用户昵称、用户UID'])->label(false);
        $text = Html::tag('i','',['class'=>'layui-icon layui-icon-search layuiadmin-button-btn']);
        echo Html::submitButton($text,['class'=>'layui-btn']);
        ActiveForm::end();


        ?>

    </div>
</div>

