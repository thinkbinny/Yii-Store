<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;

/* @var $this yii\web\View */
/* @var $model backend\models\Links */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs("
layui.use('form', function(){
  var form = layui.form;
 form.render();
});
");
?>


    <?php $form = ActiveForm::begin([
        'options'=>['class'=>'layui-form'],
        'fieldClass'        =>'backend\widgets\ActiveField',
    ]);

        echo $form->field($model, 'title')->textInput(['maxlength' => true,'style'=>'width:550px;'])->hint('（网站关键字）');
        echo $form->field($model, 'pic_url')
            ->widget('common\plugin\uploads\Uploads',['type'=>0,'options'=>['size'=>550]]);

        echo $form->field($model, 'url')->textInput(['maxlength' => true,'style'=>'width:550px;'])->hint('（格式: http://www.zhitianjie.com）');
        echo $form->field($model, 'type')->dropDownList($model->getType(), ['encode' => false]);
        echo $form->field($model, 'remark')->textarea(['maxlength' => true,'style'=>'width:550px;height:100px;'])->hint('（备注重要信息）');
        echo $form->field($model, 'status')->radioList($model->getStatus(),[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                return $return;
            }
        ]);
        echo $form->field($model, 'sort')->textInput(['maxlength' => true,'style'=>'width:150px;'])->hint('（数值越小排序越前）');
        $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn']);
        echo Html::tag('div',$Button,['class'=>'layui-form-button']);
    ActiveForm::end();
    ?>
