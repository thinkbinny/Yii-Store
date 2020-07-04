<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;

use \yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\Links */
/* @var $form yii\widgets\ActiveForm */
$NewsUrl    = Url::to(['wx-materials/lists','type'=>'2']);
$ImageUrl   = Url::to(['wx-materials/lists','type'=>'3']);
$VideoUrl   = Url::to(['wx-materials/lists','type'=>'4']);
if(empty($model->msg_type) || $model->msg_type==1){
    $model->msg_type =1;
    $MsgTypeStyle = 'display: block;';
    $MediaIdStyle = 'display: none;';
}else{
    $MsgTypeStyle = 'display: none;';
    $MediaIdStyle = 'display: block;';
}
$Url   = Url::to(['wx-materials/lists','type'=>$model->msg_type]);
$this->registerJs("
var Url = '{$Url}';
var MsgTypeVal = {$model->msg_type};
layui.use('form', function(){
  var form = layui.form;
  form.render();    
    form.on('radio(MsgType)', function(data){    
          MsgTypeVal = data.value;      
          if(MsgTypeVal == 1){
            $('#MsgTypeText').css('display','block');
            $('#MsgTypeNews').css('display','none');
          }else{
            $('#MsgTypeText').css('display','none');
            $('#MsgTypeNews').css('display','block');
            $('#MediaIdText').val('');
             if(MsgTypeVal==2){                
                Url = '{$NewsUrl}';
                $('#MediaIdText').val($('#msgType_2').attr('data-default'));
             }else if(MsgTypeVal==3){
                Url = '{$ImageUrl}';
                $('#MediaIdText').val($('#msgType_3').attr('data-default'));
             }else if(MsgTypeVal==4){
                Url = '{$VideoUrl}';
                $('#MediaIdText').val($('#msgType_4').attr('data-default'));
             }else{
                $('#MediaIdText').val('');
             }
          }
    });  
});
$('.media_open').click(function(){
    layer.open({
      title:'请选择素材',  
      shadeClose: true,
      shade: 0.8,
      area: ['1000px', '90%'],
      type: 2, 
      content: Url //这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']
    });
})
$('#msgType_{$model->msg_type}').attr('data-default','{$model->media_id}');
 function wxSelectFile(val){
    $('#MediaIdText').val(val);    
    $('#msgType_'+MsgTypeVal).attr('data-default',val);
 }
",\yii\web\View::POS_END);
$this->registerJs("
function mySubmit(){ 
   $('.ajax-submit').click();
}
",\yii\web\View::POS_END);
?>


    <?php $form = ActiveForm::begin([
        'options'=>['class'=>'layui-form'],
        'fieldClass'        =>'backend\widgets\ActiveField',
    ]);
        echo $form->field($model, 'name')->textInput(['maxlength' => true,'style'=>'width:550px;']);
        echo $form->field($model, 'keyword')
            ->label('关键字')
            ->textInput(['maxlength' => true,'style'=>'width:450px;'])
            ->hint("多个关键字，请以英文逗号隔开（','）");


        echo $form->field($model, 'msg_type')->radioList($model->getMsgType(),[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input lay-filter=\"MsgType\" data-default=\"\" id=\"msgType_{$value}\"  name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                return $return;
            }
        ]);
        echo $form->field($model, 'content',['options'=>['style'=>$MsgTypeStyle,'id'=>'MsgTypeText']])
            ->textarea(['maxlength' => true,'style'=>'width:550px;height:100px;'])
            ->hint("");
        $material_button =  Html::button('选择',['data-url'=>'','class'=>'layui-btn layui-btn-normal media_open','style'=>'display: inline;',]);
        echo $form->field($model, 'media_id',['options'=>['style'=>$MediaIdStyle,'id'=>'MsgTypeNews'],'template'=>"{label}\n<div {width} class=\"layui-input-inline\"><div class=\"layui-input-inline\">{input}</div>".$material_button."\n{error}</div>\n{hint}"])
            ->label('素材ID')
            ->textInput(['maxlength' => true,'style'=>'width:420px;display: inline;','id'=>'MediaIdText'])
            ->hint("");

        $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
        echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ActiveForm::end();
    ?>

