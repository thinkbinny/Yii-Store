<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;
use yii\widgets\ActiveField;

/* @var $this yii\web\View */
/* @var $model backend\models\Links */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs("
function mySubmit(){ 
   $('.ajax-submit').click();
}
",\yii\web\View::POS_END);
$this->registerJs("


$('.resetsecret').click(function(){
        var Url = $(this).attr('data-url');
        $.ajax({
            type:'get',
            url:Url,
            data:{},
            beforeSend: function () {
                parent.layer.load(0,{shade: [0.2, '#000'],scrollbar: false,area: ['60px', '30px']});
            },
            complete: function () {
                parent.layer.closeAll('loading');
            },
            success: function (data) {
                if(data.status==true){
                    $('#app_secret').val(data.message);
                }else{
                    popup.alert(data.message);
                }
            },
            error: function (e, jqxhr, settings, exception) {
                popup.alert(\"服务器异常！\");
            }
        })
})


");
?>


    <?php $form = ActiveForm::begin([
        'options'=>['class'=>'layui-form'],

    ]);
        if($model->isNewRecord){
            $model->status      = 1;
            $model->app_id      = $model->CreateAppId();
            $model->app_secret  = $model->ResetSecret();
        }
            echo $form->field($model, 'app_id')
                ->textInput(['maxlength' => true,'style'=>'width:300px;','readonly'=>true])
                ->hint('（此参数为系统自动生成）');//重复请刷新页面
            echo $form->field($model, 'app_secret')
                ->textInput(['maxlength' => true,'style'=>'width:300px;','readonly'=>true,'id'=>'app_secret'])
                ->hint('（此参数为系统自动生成）');
        $html = Html::tag('label','',['class'=>'layui-form-label']).Html::button('重新生成密钥',['class'=>'layui-btn resetsecret','data-url'=>\yii\helpers\Url::to(['resetsecret'])]);
        echo Html::tag('div',$html,['class'=>'layui-form-item']);
        echo $form->field($model, 'app_name')->textInput(['maxlength' => true,'style'=>'width:300px;']);
       /* if(!$model->isNewRecord){
            echo $form->field($model, 'app_secret')
                ->textInput(['maxlength' => true,'style'=>'width:300px;','readonly'=>true])
                ->hint('（密钥只显示一次）');
            $html = Html::tag('label','',['class'=>'layui-form-label']).Html::button('生成密钥',['class'=>'layui-btn']);
            echo Html::tag('div',$html,['class'=>'layui-form-item']);
        }*/
        echo $form->field($model, 'app_desc')->textarea(['maxlength' => true,'style'=>'width:550px;height:100px;']);
        echo $form->field($model, 'status')->radioList($model->getStatus(),[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                return $return;
            }
        ]);

        $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
        echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ActiveForm::end();
    ?>
