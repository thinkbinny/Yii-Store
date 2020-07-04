<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\search\MenuSearch */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs("

");

?>
<div class="page-toolbar">
    <div class="layui-btn-group">

        <?php
        echo Html::a('&nbsp;添加',['create'],[
            'class'         => 'layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine ajax-iframe-popup',
            'data-iframe'   => "{width: '650px', height: '500px', title: '添加属性'}",
        ]);

        echo Html::a('&nbsp;启用','javascript:;',[
            'data-name'     => 'status',
            'data-value'    => '1',
            'data-url'      => Url::to(['status']),
            'data-form'     => 'ids',
            'class'         => 'ajax-status-post confirm layui-btn layui-btn-primary layui-icon layui-icon-play',
        ]);
        echo Html::a('&nbsp;禁用','javascript:;',[
            'data-name'     => 'status',
            'data-value'    => '0',
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

        echo $form->field($model, 'model_type',['options'=>['class'=>'layui-input-inline','style'=>'width: 120px;']])
            ->dropDownList($model->getModelType(),['prompt' => '属性类型'])->label(false);

        echo $form->field($model, 'status',['options'=>['class'=>'layui-input-inline','style'=>'width: 120px;']])
            ->dropDownList($model->getStatus(),['prompt' => '是否显示'])->label(false);
        $placeholder = '请输入属性名称' ;
        echo $form->field($model, 'name',['options'=>['class'=>'layui-input-inline','style'=>'width: 300px;']])
            ->textInput(['placeholder' => $placeholder])->label(false);
        $text = Html::tag('i','',['class'=>'layui-icon layui-icon-search layuiadmin-button-btn']);
        echo Html::submitButton($text,['class'=>'layui-btn']);
        ActiveForm::end();
        ?>

    </div>
</div>

