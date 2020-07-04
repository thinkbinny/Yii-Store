<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Links */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs("
function mySubmit(){ 
   $('.ajax-submit').click();
}
",\yii\web\View::POS_END);
$this->registerJs("
layui.use('form', function(){
  var form = layui.form;
 form.render();
});

layui.use('laydate', function(){
  var laydate = layui.laydate;
  
  //执行一个laydate实例
  laydate.render({
    elem: '#send_time' //指定元素
    ,type: 'datetime'
    ,min: '".date('Y-m-d H:i:s')."'
  });
});

");
    $form = ActiveForm::begin([
        'options'=>['class'=>'layui-form'],
    ]);
        if($model->isNewRecord){
        echo $form->field($model, 'uid')
            ->textInput(['maxlength' => true,'style'=>'width:250px;','placeholder'=>'请填写标题'])
            ->label('接收UID')
            ->hint('空值代表全部用户，则用户UID');
         $model->send_type = 0;
        }
        echo $form->field($model, 'title')->textInput(['maxlength' => true,'style'=>'width:450px;','placeholder'=>'请填写标题'])->hint('');
        echo $form->field($model, 'type')->dropDownList($model->getType(), ['encode' => false]);
        echo $form->field($model, 'content')->textarea(['maxlength' => true,'style'=>'width:650px;height:150px;','placeholder'=>'请填写内容'])->hint('');
        if($model->isNewRecord){
        echo $form->field($model, 'send_type')->radioList($model->getSendType(),[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                return $return;
            }
        ]);

        echo $form->field($model, 'send_time')->textInput(['maxlength' => true,'placeholder'=>'请选择时间','id'=>'send_time','style'=>'width:180px;'])->hint('空值立即发送');
        }
        $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
        echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ActiveForm::end();
    ?>
