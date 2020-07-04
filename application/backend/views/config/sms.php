
<?php

use yii\helpers\Html;

use backend\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '短信通知';
$this->params['breadcrumbs'][] = ['label' => '商城设置', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;

$this->params['thisUrl'] = 'config/sms';
$css = <<<CSS
.layui-form-label{width:200px;}
.layui-form-item .layui-input-inline{width:500px;}
.info{margin-bottom: 25px;margin-top: 3px;}
CSS;

$this->registerCss($css);
$this->registerJs("
layui.use('form', function(){
  var form = layui.form;
 form.render();
});
");

?>





<div class="layuimini-container">
    <div class="layuimini-main">
        <div class="layui-main" >
    <?php
    $form = ActiveForm::begin([
        'options'=>['class'=>'layui-form'],
    ]);
    ?>
            <fieldset class="layui-elem-field layui-field-title" >
                <legend>短信通知（阿里云短信）</legend>
            </fieldset>
            <div style="width: 800px;">
     <?php
    echo $form->field($model,'data[aliyun][AccessKeyId]')
        ->textInput(['placeholder'=>'请填写阿里云AccessKeyId'])
        ->label('AccessKeyId')
        ->hint('')
        ->width(500);

    echo $form->field($model, 'data[aliyun][AccessKeySecret]')
        ->textInput(['placeholder'=>'请填写阿里云AccessKeySecret'])
        ->label('AccessKeySecret')
        ->hint('')
        ->width(500);
     echo $form->field($model, 'data[aliyun][sign]')
         ->textInput(['placeholder'=>'请填写阿里云短信签名'])
         ->label('短信签名')
         ->hint('')
         ->width(500);
    ?>
            </div>
            <fieldset class="layui-elem-field layui-field-title" >
                <legend>新付款订单提醒</legend>
            </fieldset>
            <div style="width: 800px;">
    <?php
    echo $form->field($model, 'data[order_pay][is_open]')
        ->checkboxList([1=>'开启',0=>'关闭'],[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                return $return;
            }
        ])
        ->label('是否开启短信提醒')
        ->hint('');
    echo $form->field($model, 'data[order_pay][template_code]')
        ->textInput(['placeholder'=>'请填写阿里云模板ID Template Code'])
        ->label('模板ID<span class="tpl-form-line-small-title">Template Code</span>')
        ->hint('例如：SMS_139800030<br>模板内容：您有一条新订单，订单号为：${order_no}，请注意查看。')
        ->width(500);
    echo $form->field($model, 'data[order_pay][accept_phone]')
        ->textInput(['placeholder'=>'请填写接收手机号'])
        ->label('接收手机号')
        ->hint('注：如需填写多个手机号，可用英文逗号 , 隔开<br>接收测试：<a style="color: #0e90d2;" data-msg-type="order_pay" href="javascript:void(0);">点击发送</a>')
        ->width(500);

    ?>
            </div>


            <div class="layui-form-item" style="margin-bottom: 50px;">
                <label class="layui-form-label"></label>
                <div class="layui-input-inline">
                    <?= Html::submitButton('提交', ['style'=>'width:100%;','class' => 'layui-btn ajax-submit']) ?>
                </div>
            </div>

            <?php
    ActiveForm::end();
    ?>
    </div>
  </div>
</div>
