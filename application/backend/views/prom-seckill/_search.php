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

        <?php
        echo Html::a('&nbsp;添加',['create'],[
            'class'         => 'layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine ajax-iframe-popup',
            'data-iframe'   => "{width: '1100px', height: '90%', title: '添加活动',scrollbar:'Yse'}",
        ]);

        echo Html::a('&nbsp;开启活动','javascript:;',[
            'data-name'     => 'status',
            'data-value'    => '0',
            'data-url'      => Url::to(['status']),
            'data-form'     => 'ids',
            'class'         => 'ajax-status-post confirm layui-btn layui-btn-primary layui-icon layui-icon-play',
        ]);
        echo Html::a('&nbsp;取消活动','javascript:;',[
            'data-name'     => 'status',
            'data-value'    => '-1',
            'data-url'      => Url::to(['status']),
            'data-form'     => 'ids',
            'class'         => 'ajax-status-post confirm layui-btn layui-btn-primary layui-icon layui-icon-pause',
        ]);
        echo Html::a('&nbsp;删除','javascript:;',[
            'class'=>'ajax-delete confirm layui-btn layui-btn-primary layui-icon layui-icon-close',
            'data-url'      => Url::to(['delete']),
            'data-form'     => 'ids',
        ]);

        ?>


    </div>
    <div class="page-filter pull-right layui-search-form">
        <?php
        $form = ActiveForm::begin([
            'fieldClass' => 'backend\widgets\ActiveSearch',
            'action' => ['index'],
            'method' => 'get',
            'options'=>['class'=>'layui-form'],
        ]);


        echo $form->field($model, 'status',['options'=>['class'=>'layui-input-inline','style'=>'width: 120px;']])
            ->dropDownList($model->getStatus(),['prompt' => '商品状态'])->label(false);

        echo $form->field($model, 'value',['options'=>['class'=>'layui-input-inline','style'=>'width: 250px;']])
            ->textInput(['placeholder' => '请输入商品标题'])->label(false);
        $text = Html::tag('i','',['class'=>'layui-icon layui-icon-search layuiadmin-button-btn']);
        echo Html::submitButton($text,['class'=>'layui-btn']);
        ActiveForm::end();
        ?>

    </div>
</div>

