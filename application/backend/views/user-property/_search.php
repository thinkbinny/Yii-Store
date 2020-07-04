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
        laydate.render({
            elem: '#searchTime'
            ,range: true
        });
    });    
");

?>
<div class="page-toolbar clearfix">
    <div class="layui-btn-group" style="">



    </div>
    <div class="page-filter pull-right layui-search-form">

        <?
        $form = ActiveForm::begin([
            'fieldClass' => 'backend\widgets\ActiveSearch',
            'action' => ['index'],
            'method' => 'get',
            'options'=>['class'=>'layui-form'],
        ]);

        $data = $model->getScene();
        $placeholder = '请输入用户UID、用户昵称';
        echo $form->field($model, 'scene',['options'=>['class'=>'layui-input-inline','style'=>'width: 125px;']])
            ->dropDownList($data,['prompt' => '变动场景'])->label(false);

        echo $form->field($model, 'searchTime',['options'=>['class'=>'layui-input-inline','style'=>'width: 200px;']])
            ->textInput(['class'=>'layui-input','placeholder'=>'搜索日期范围','id'=>'searchTime','readonly'=>true])->label(false);

        echo $form->field($model, 'q',['options'=>['class'=>'layui-input-inline','style'=>'width: 300px;']])
            ->textInput(['class'=>'layui-input','placeholder'=>$placeholder])->label(false);
        echo  Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'layui-btn']);
        ActiveForm::end();
        ?>

    </div>
</div>

