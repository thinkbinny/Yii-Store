<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\search\MenuSearch */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs("
layui.use('laydate', function(){
  var laydate = layui.laydate;
      //日期时间范围
      laydate.render({
        elem: '#rangedate'
        ,type: 'date'
        ,range: true
      });
  });
");

?>
<div class="page-toolbar clearfix">
    <div class="layui-btn-group">

    </div>
    <div class="page-filter pull-right layui-search-form">
        <?php

        $form = ActiveForm::begin([
            'fieldClass' => 'backend\widgets\ActiveSearch',
            'action' => ['index'],
            'method' => 'get',
            'options'=>['class'=>'layui-form'],
        ]);

        echo $form->field($model, 'type',['options'=>['class'=>'layui-input-inline','style'=>'width: 120px;']])
            ->dropDownList($model->getType(),['prompt' => '售后类型'])->label(false);

        echo $form->field($model, 'status',['options'=>['class'=>'layui-input-inline','style'=>'width: 150px;']])
            ->dropDownList($model->getStatus(),['prompt' => '处理状态'])->label(false);

        echo $form->field($model, 'rangedate',['options'=>['class'=>'layui-input-inline','style'=>'width: 190px;']])
            ->textInput(['placeholder' => '请选择日期范围','id'=>'rangedate'])->label(false);
        echo $form->field($model, 'order_sn',['options'=>['class'=>'layui-input-inline','style'=>'width: 250px;']])
            ->textInput(['placeholder' => '请输入订单号'])->label(false);
        $text = Html::tag('i','',['class'=>'layui-icon layui-icon-search layuiadmin-button-btn']);
        echo Html::submitButton($text,['class'=>'layui-btn']);
        ActiveForm::end();
        ?>

    </div>
</div>

