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
?>

    <?php $form = ActiveForm::begin([
        'options'=>['class'=>'layui-form'],

    ]);

        echo $form->field($model, 'title')
            ->textInput(['maxlength' => true,'style'=>'width:250px;','placeholder'=>'请输入网站关键字']);

        echo $form->field($model, 'image_id')
            ->widget('extensions\uploads\Uploads',['msg'=>'建议上传50x100像素或等比例尺寸的图片']);

        echo $form->field($model, 'url')->textInput(['maxlength' => true,'style'=>'width:350px;','placeholder'=>'请输入网站网址'])
            ->hint('（格式: http://www.zhitianjie.com）');
        echo $form->field($model, 'type')->dropDownList($model->getType(), ['encode' => false]);
        echo $form->field($model, 'remark')->textarea(['maxlength' => true,'style'=>'width:550px;height:100px;','placeholder'=>'备注重要信息']);

        $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
        echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ActiveForm::end();
    ?>
