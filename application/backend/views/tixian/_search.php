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

        <?php
       /* echo Html::a('&nbsp;审核通过','javascript:;',[
            'data-name'     => 'status',
            'data-value'    => '1',
            'data-url'      => Url::to(['status']),
            'data-form'     => 'ids',
            'class'         => 'ajax-status-post confirm layui-btn layui-btn-primary layui-icon layui-ayui-icon-ok',
        ]);
        echo Html::a('&nbsp;不通过','javascript:;',[
            'data-name'     => 'status',
            'data-value'    => '0',
            'data-url'      => Url::to(['status']),
            'data-form'     => 'ids',
            'class'         => 'ajax-status-post confirm layui-btn layui-btn-primary layui-icon layui-icon-pause',
        ]);*/
        ?>

    </div>
    <div class="page-filter pull-right layui-search-form">

        <?
        $form = ActiveForm::begin([
            'fieldClass' => 'backend\widgets\ActiveSearch',
            'action' => ['index'],
            'method' => 'get',
            'options'=>['class'=>'layui-form'],
        ]);

        $data = $model->getType();
        $placeholder = '请输入用户UID、用户昵称';
        echo $form->field($model, 'type',['options'=>['class'=>'layui-input-inline','style'=>'width: 125px;']])
            ->dropDownList($data,['prompt' => '提现方式'])->label(false);

        echo $form->field($model, 'searchTime',['options'=>['class'=>'layui-input-inline','style'=>'width: 200px;']])
            ->textInput(['class'=>'layui-input','placeholder'=>'申请日期范围','id'=>'searchTime','readonly'=>true])->label(false);

        echo $form->field($model, 'q',['options'=>['class'=>'layui-input-inline','style'=>'width: 300px;']])
            ->textInput(['class'=>'layui-input','placeholder'=>$placeholder])->label(false);
        echo  Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'layui-btn']);
        ActiveForm::end();
        ?>

    </div>
</div>

