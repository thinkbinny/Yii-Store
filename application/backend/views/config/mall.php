
<?php

use yii\helpers\Html;

use backend\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '商城设置';
$this->params['breadcrumbs'][] = ['label' => '商城设置', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;

$this->params['thisUrl'] = 'config/mall';
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
                <legend>基本设置</legend>
            </fieldset>
            <div style="width: 800px;">
     <?php
    echo $form->field($model,'data[name]')
        ->textInput(['placeholder'=>'请填写商城名称'])
        ->label('商城名称')
        ->hint('')
        ->width(500);

    echo $form->field($model, 'data[delivery_type]')
        ->checkboxList([1=>'快递配送',2=>'上门自提'],[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input lay-skin='primary' name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"checkbox\">";
                return $return;
            }
        ])
        ->label('配送方式')
        ->hint('注：配送方式至少选择一个')
        ->width(500);

    ?>
            </div>
            <fieldset class="layui-elem-field layui-field-title" >
                <legend>订单流程设置</legend>
            </fieldset>
            <div style="width: 800px;">
    <?php

    echo $form->field($model, 'data[close_days]',[
        'template' => '{label}
            <div style="width:500px;" class="layui-input-inline">
            <div class="input-group">{input}<span class="input-group-addon">天后自动关闭</span></div>
            </div>{hint}',
    ])->textInput([
        'placeholder'=>'',
    ])->label('未支付订单')
        ->hint('订单下单未付款，n天后自动关闭，设置0天不自动关闭');

    echo $form->field($model, 'data[receive_days]',[
        'template' => '{label}
            <div style="width:500px;" class="layui-input-inline">
            <div class="input-group">{input}<span class="input-group-addon">天后自动确认收货</span></div>
            </div>{hint}',
    ])->textInput([
        'placeholder'=>'',
    ])->label('已发货订单')
        ->hint('如果在期间未确认收货，系统自动完成收货，设置0天不自动收货');

    echo $form->field($model, 'data[refund_days]',[
        'template' => '{label}
            <div style="width:500px;" class="layui-input-inline">
            <div class="input-group">{input}<span class="input-group-addon">天内允许申请售后</span></div>
            </div>{hint}',
    ])->textInput([
        'placeholder'=>'',
    ])->label('已完成订单')
        ->hint('订单完成后 ，用户在n天内可以发起售后申请，设置0天不允许申请售后');
    ?>
            </div>
            <fieldset class="layui-elem-field layui-field-title" >
                <legend>运费设置</legend>
            </fieldset>
    <?php
    echo $form->field($model,'data[freight_rule]')
        ->radioList([1=>'叠加',2=>'以最低运费结算',3=>'以最高运费结算'],[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $data = [
                  1=>'订单中的商品有多个运费模板时，将每个商品的运费之和订为订单总运费',
                  2=>'订单中的商品有多个运费模板时，取订单中运费最少的商品的运费计为订单总运费',
                  3=>'订单中的商品有多个运费模板时，取订单中运费最多的商品的运费计为订单总运费',
                ];
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";

                $return .= '<div class="info layui-word-aux">'.$data[$value].'</div>';

                return $return;
            }
        ])
        ->label('运费组合策略')
        ->hint('');

    ?>
            <fieldset class="layui-elem-field layui-field-title" >
                <legend>物流查询API</legend>
            </fieldset>
            <div style="width: 800px;">
            <?php
            echo $form->field($model,'data[kuaidi100][customer]')
                ->textInput(['placeholder'=>'请填写快递100 Customer'])
                ->label('快递100 Customer')
                ->hint('用于查询物流信息，<a style="color: #0e90d2;" href="https://www.kuaidi100.com/openapi/" target="_blank">快递100申请</a>')
                ->width(500);
            echo $form->field($model,'data[kuaidi100][key]')
                ->textInput(['placeholder'=>'请填写快递100 Key'])
                ->label('快递100 Key')
                ->hint('')
                ->width(500);
            ?>
            <div class="layui-form-item" style="margin-bottom: 50px;">
                <label class="layui-form-label"></label>
                <div class="layui-input-inline">
                    <?= Html::submitButton('提交', ['style'=>'width:100%;','class' => 'layui-btn ajax-submit']) ?>
                </div>
            </div>
            </div>
            <?php
    ActiveForm::end();
    ?>
    </div>
  </div>
</div>
