
<?php

use yii\helpers\Html;

use backend\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '积分设置';
$this->params['breadcrumbs'][] = ['label' => '营销管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss("
.layui-form-label{width:200px;}
.layui-form-item .layui-input-inline{width:500px;}
");
$this->registerJs("
layui.use('form', function(){
  var form = layui.form;
 form.render();
});
");
$this->registerCss("

");
?>





<div class="layuimini-container">
    <div class="layuimini-main">
    <?=$this->render('_tab_menu');?>
        <div class="layui-main" >

    <?php
    $form = ActiveForm::begin([
        'options'=>['class'=>'layui-form'],
    ]);
    ?>
            <fieldset class="layui-elem-field layui-field-title" >
                <legend>基本设置</legend>
            </fieldset>
     <?php
    echo $form->field($model,'data[name]')
        ->textInput(['placeholder'=>'请填写积分名称'])
        ->label('积分名称')
        ->hint('注：修改积分名称后，在买家端的所有页面里，看到的都是自定义的名称<br>注：商家使用自定义的积分名称来做品牌运营。如京东把积分称为“京豆”，淘宝把积分称为“淘金币”')
        ->width(500);
    echo $form->field($model, 'data[describe]')
        ->textarea(['style'=>'height: 150px;','placeholder'=>'请输入积分说明/规则'])
        ->label('积分说明')
        ->width(800);
    ?>
            <fieldset class="layui-elem-field layui-field-title" >
                <legend>积分赠送</legend>
            </fieldset>
    <?php

    echo $form->field($model,'data[is_points_gift]')
        ->radioList([1=>'开启',0=>'关闭'],[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                return $return;
            }
        ])
        ->label('是否开启购物送积分')
        ->hint('注：如开启则订单完成后赠送用户积分<br>积分赠送规则：1.订单确认收货已完成；2.已完成订单超出后台设置的申请售后期限')
        ->width(800);
    echo $form->field($model, 'data[gift_ratio]',[
        'template' => '
            {label}
            <div style="width:500px;" class="layui-input-inline">
            <div class="input-group">{input}<span class="input-group-addon">%</span></div>
            </div>
            {hint}
            ',
    ])
        ->label('积分赠送比例')
        ->textInput(['placeholder'=>'请填写积分赠送比例 '])
        ->hint('注：赠送比例请填写数字0~100；订单的运费不参与积分赠送<br>例：订单付款金额(100.00元) * 积分赠送比例(100%) = 实际赠送的积分(100积分) ');
    ?>
            <fieldset class="layui-elem-field layui-field-title" >
                <legend>积分抵扣</legend>
            </fieldset>
    <?php
    echo $form->field($model,'data[is_points_discount]')
        ->radioList([1=>'允许',0=>'不允许'],[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                return $return;
            }
        ])
        ->label('是否允许下单使用积分抵扣')
        ->hint('注：如开启则用户下单时可选择使用积分抵扣订单金额')
        ->width(800);

    echo $form->field($model, 'data[discount_ratio]',[
        'template' => '
            {label}
            <div style="width:500px;" class="layui-input-inline">
            <div class="input-group"><span class="input-group-addon">1个积分可抵扣</span>{input}<span class="input-group-addon">元</span></div>
            </div>
            {hint}
            ',
    ])->textInput([
        'placeholder'=>'请填写积分抵扣比例',
    ])->label('积分抵扣比例')
        ->hint('例如：1积分可抵扣0.01元，100积分则可抵扣1元，1000积分则可抵扣10元');

    echo $form->field($model, 'data[full_order_price]',[
        'template' => '
            {label}
            <div style="width:500px;" class="layui-input-inline">
            <div class="input-group"><span class="input-group-addon">订单满</span>{input}<span class="input-group-addon">元</span></div>
            </div>
            {hint}
            ',
    ])->textInput([
        'placeholder'=>'请填写订单满',
    ])->label('抵扣条件');
    echo $form->field($model, 'data[max_money_ratio]',[
        'template' => '
            {label}
            <div style="width:500px;" class="layui-input-inline">
            <div class="input-group"><span class="input-group-addon">最高可抵扣金额</span>{input}<span class="input-group-addon">%</span></div>
            </div>
            {hint}
            ',
    ])->textInput([
        'placeholder'=>'请输入最高可抵扣百分比',
    ])->label('&nbsp;')
        ->hint('温馨提示：例如订单金额100元，最高可抵扣10%，此时用户可抵扣10元');
    ?>
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
