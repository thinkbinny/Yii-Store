<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $form yii\widgets\ActiveForm */
$url = \yii\helpers\Url::to(['wx-materials/lists']);
$this->registerJs("
layui.use('form', function(){
  var form = layui.form;
 form.render();
});

$('.media_open').click(function(){
    layer.open({
      title:false,  
      shadeClose: true,
      shade: 0.8,
      area: ['1000px', '90%'],
      type: 2, 
      content: '{$url}' //这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']
    });
})


");

$this->registerJs("
 function wxSelectFile(val){
    $('#MediaIdText').val(val);
 }
 function mySubmit(){ 
   $('.ajax-submit').click();
}
",\yii\web\View::POS_END);

?>
<style type="text/css">
    .ui-admin .layui-main .layui-form-label{width:200px;}
    .ui-admin .layui-main .layui-form-button{padding-left:200px;}
</style>
    <?php

    $form = ActiveForm::begin([
        'options'=>['class'=>'layui-form'],
        'fieldClass'        =>'backend\widgets\ActiveField',
    ]);
        echo $form->field($model, 'pid')->dropDownList([0 => '一级菜单']+$treeArr, ['encode' => false]);
        echo $form->field($model, 'name')->textInput(['maxlength' => true])->width(300);
        echo $form->field($model, 'type')->dropDownList($model->getType());
        echo $form->field($model, 'key')->textInput(['maxlength' => true])->hint('菜单KEY值，用于消息接口推送，不超过128字节')->width(300);
        echo $form->field($model, 'url')->textInput(['maxlength' => true])->hint('网页 链接，用户点击菜单可打开链接，不超过1024字节(view、miniprogram类型必须)')->width(350);

        //echo $form->field($model, 'media_id')->textInput(['maxlength' => true])->hint('调用新增永久素材接口返回的合法media_id')->width(200);

    $material_button =  Html::button('选择',['class'=>'layui-btn layui-btn-normal media_open','style'=>'display: inline;',]);
    echo $form->field($model, 'media_id',['options'=>['class'=>'layui-form-item'],'template'=>"{label}\n<div {width} class=\"layui-input-inline\"><div style='width: auto;' class=\"layui-input-inline\">{input}</div>".$material_button."\n{error}</div>\n{hint}"])

        ->textInput(['maxlength' => true,'style'=>'width:420px;display: inline;','id'=>'MediaIdText'])
        ->hint("");


        echo $form->field($model, 'appid')->textInput(['maxlength' => true])->hint('小程序的appid（仅认证公众号可配置）')->width(200);
        echo $form->field($model, 'pagepath')->textInput(['maxlength' => true])->hint('小程序的页面路径')->width(300);


    $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
    echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ActiveForm::end();

    ?>
