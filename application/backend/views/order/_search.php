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
<div class="page-toolbar">
    <div class="layui-btn-group">

        <?php
        $action     = Yii::$app->controller->action->id;

        echo Html::a('<i class="fa fa-download"></i>&nbsp;订单导出',['export'],[
            'class'         => 'layui-btn layui-btn-primary',

        ]);
        if($action == 'index' || $action == 'delivery-list'){
            echo Html::a('<i class="fa fa-upload"></i>&nbsp;批量发货',['batchdelivery'],[
                'class'     => 'layui-btn layui-btn-primary ajax-iframe-popup',
                'data-iframe'   => "{width: '500px', height: '320px', title: '批量发货'}",
            ]);
        }
        ?>


    </div>
    <div class="page-filter pull-right layui-search-form">
        <?php
        $action     = Yii::$app->controller->action->id;
        $form = ActiveForm::begin([
            'fieldClass' => 'backend\widgets\ActiveSearch',
            'action' => [$action],
            'method' => 'get',
            'options'=>['class'=>'layui-form'],
        ]);

        echo $form->field($model, 'delivery_type',['options'=>['class'=>'layui-input-inline','style'=>'width: 120px;']])
            ->dropDownList($model->deliveryType,['prompt' => '配送方式'])->label(false);

        echo $form->field($model, 'extract_shop_id',['options'=>['class'=>'layui-input-inline','style'=>'width: 150px;']])
            ->dropDownList($model->extractShopIdText,['prompt' => '自提门店名称'])->label(false);

        echo $form->field($model, 'rangedate',['options'=>['class'=>'layui-input-inline','style'=>'width: 190px;']])
            ->textInput(['placeholder' => '请选择日期范围','id'=>'rangedate'])->label(false);
        echo $form->field($model, 'q',['options'=>['class'=>'layui-input-inline','style'=>'width: 250px;']])
            ->textInput(['placeholder' => '请输入订单号/用户昵称'])->label(false);
        $text = Html::tag('i','',['class'=>'layui-icon layui-icon-search layuiadmin-button-btn']);
        echo Html::submitButton($text,['class'=>'layui-btn']);
        ActiveForm::end();
        ?>

    </div>
</div>

