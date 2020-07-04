<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;

$setting['image'] = '';
/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs("
function mySubmit(){ 
   $('.ajax-submit').click();
}
",\yii\web\View::POS_END);
$this->registerJs("
layui.use(['layer', 'form'], function(){
  var layer = layui.layer,form = layui.form;
   form.render(); 
     form.on('select(pattern)', function(data){
            $('#modelfield-pattern').val(data.value);
     }); 
     form.on('select(formtype)', function(data){
            
            var setting = 'setting-type-'+data.value;
            var html    = $('#'+setting).html();
            $('.form-formt').html(html);form.render(); 
     }); 
     
});
");
$this->registerCss("
.ui-admin .layui-main .relevantsetting .layui-form-item .layui-form-label{width:180px !important;}
.field-modelfield-pattern{float: left;}
.layui-form-select{float: left;}
");
///引入
$setting = require(__DIR__.'/_setting.php');
$html = '';
foreach ($setting as $key=>$val){
    $html .='<div style="display: none;" id="setting-type-'.$key.'">'.$val.'</div>';
}
echo $html;
?>

    <?php
    $form = ActiveForm::begin([
        'options'=>['class'=>'layui-form'],

    ]);

        echo $form->field($model, 'isrequired')->radioList($model->getIsrequired(),[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                return $return;
            }
        ]);

        if($model->isNewRecord){
            $model->model_id = Yii::$app->request->get('model_id');
        }
        echo $form->field($model, 'model_id',['options'=>['style'=>'display: none;']])->hiddenInput();
        echo $form->field($model, 'field')->textInput(['maxlength' => true,'placeholder'=>'请输入字段名'])->hint('只能由英文字母、数字和下划线组成，并且仅能字母开头，不以下划线结尾')->width(300);
        echo $form->field($model, 'name')->textInput(['maxlength' => true,'placeholder'=>'请输入字段别名'])->hint('例如：文章标题')->width(300);
        echo $form->field($model, 'tips')->textarea(['maxlength' => true,'style'=>'padding: 10px;height: 80px;','placeholder'=>'请输入字段提示'])->hint('显示在字段别名下方作为表单输入提示 	')->width(500);
        if($model->isNewRecord){
            echo $form->field($model, 'formtype')->dropDownList($model->getFormtype(),['encode' => true,'lay-filter'=>'formtype','prompt' => '请选择']);
        }else{
            echo $form->field($model, 'formtype')->dropDownList($model->getFormtype(),['disabled'=>'disabled','encode' => true,'lay-filter'=>'formtype','prompt' => '请选择']);
        }
        if(empty($model->formtype)){

            $formtypehtml = '&nbsp;';
        }else{
            $formtypehtml = $setting[$model->formtype];
        }
                echo '<div>
                <div class="layui-form-item relevantsetting">
                    <label class="layui-form-label" >相关参数</label>
                       <div style="width:800px;border: 1px dashed #ccc;padding: 10px;" class="layui-input-inline form-formt">
                            '.$formtypehtml.'
                        </div>            
                    </div>
                </div>';
        echo $form->field($model, 'minlength')->textInput(['maxlength' => true,'placeholder'=>'请输入最小字符长度'])->hint('系统将在表单提交时检测数据长度范围是否符合要求，如果不想限制长度请留空')->width(200);
        echo $form->field($model, 'maxlength')->textInput(['maxlength' => true,'placeholder'=>'请输入最大字符长度'])->hint('系统将在表单提交时检测数据长度范围是否符合要求，如果不想限制长度请留空')->width(200);

        echo $form->field($model, 'pattern')->textInput(['maxlength' => true,'placeholder'=>'请输入则校验表单'])->hint(false)->width(300);
        echo ' <select id="pattern" name="pattern" class="pattern" lay-filter="pattern">
          <option value="">常用正则</option>
          <option value="/^[0-9.-]+$/1">数字</option>
          <option value="/^[0-9-]+$/">整数</option>
          <option value="/^[a-z]+$/i">字母</option>
          <option value="/^[0-9a-z]+$/i">数字+字母</option>
          <option value="/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/">E-mail</option>
          <option value="/^[0-9]{5,20}$/">QQ</option>
          <option value="/^http:\/\//">超级链接</option>
          <option value="/^(1)[0-9]{10}$/">手机号码</option>
          <option value="/^[0-9-]{6,13}$/">电话号码</option>
          <option value="/^[0-9]{6}$/">邮政编码</option>          
        </select>';
        echo $form->field($model, 'errortips')->textInput(['maxlength' => true,'placeholder'=>'请输入未通过提示出错信息'])->hint('数据校验未通过的提示信息')->width(500);



        /*

        echo $form->field($model, 'engine_type')->dropDownList($model->getEngineType(), ['encode' => false]);
        echo $form->field($model, 'extend')->dropDownList($model->getExtend(),['encode' => false]);
        //echo $form->field($model, 'remark')->textInput(['maxlength' => true])->width(500);
        echo $form->field($model, 'sort')->textInput()->hint('数值越小排序越前');*/

        $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);


        echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ActiveForm::end();

    ?>
