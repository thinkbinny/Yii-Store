<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;
$this->title = '小票打印设置';
$this->params['breadcrumbs'][] = ['label' => '商城设置', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;
$this->params['thisUrl'] = 'printer/index';
$css = <<<CSS
.layui-form-label{width:200px;}
.layui-form-item .layui-input-inline{width:500px;}
.info{margin-bottom: 25px;margin-top: 3px;}
CSS;

$this->registerCss($css);

?>
<div class="layuimini-container">
    <div class="layuimini-main" >
   <?=$this->render('_tab_menu');?>

        <?php
       $form = ActiveForm::begin([
           'options'=>['class'=>'layui-form'],
       ]);

      echo $form->field($model, 'data[is_open]')
       ->checkboxList([1=>'开启',0=>'关闭'],[
           'item' => function($index, $label, $name, $checked, $value)
           {
               $checked=$checked?"checked":"";
               $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
               return $return;
           }
       ])
       ->label('是否开启小票打印')
       ->hint('');

       echo $form->field($model, 'data[printer_id]')
           ->dropDownList($PrinterList,['prompt'=>'请选择打印机'])
           ->label('选择订单打印机')
           ->hint('');

       echo $form->field($model, 'data[order_status]')
           ->checkboxList([1=>'创建订单时',2=>'订单付款时'],[
               'item' => function($index, $label, $name, $checked, $value)
               {
                   $checked=$checked?"checked":"";
                   $return = "<input lay-skin=\"primary\" name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"checkbox\">";
                   return $return;
               }
           ])
           ->label('订单打印方式')
           ->hint('');
        ?>
        <div class="layui-form-item" style="margin-bottom: 50px;">
            <label class="layui-form-label"></label>
            <div class="layui-input-inline" style="width:100px;">
                <?= Html::submitButton('提交', ['style'=>'width:100%;','class' => 'layui-btn ajax-submit']) ?>
            </div>
        </div>
        <?php
        ActiveForm::end(); ?>

  </div>
</div>