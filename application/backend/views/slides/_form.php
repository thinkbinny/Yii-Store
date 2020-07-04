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
?>


    <?php $form = ActiveForm::begin([
        'options'=>['class'=>'layui-form'],

    ]);

        echo $form->field($model, 'title')->textInput(['maxlength' => true,'style'=>'width:250px;','placeholder'=>'请输入幻灯片名称'])->hint('');
        echo $form->field($model, 'type')->dropDownList($model->getType(), ['encode' => false]);
        echo $form->field($model, 'url')
            ->textInput(['maxlength' => true,'style'=>'width:350px;','placeholder'=>'请输入链接网址'])
            ->hint('（格式: http://www.zhitianjie.com）');
        echo $form->field($model, 'image_id')
            ->widget('extensions\uploads\Uploads',['msg'=>'']);
        echo $form->field($model, 'thumb_image_id')
            ->widget('extensions\uploads\Uploads',['msg'=>'']);



        $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
        echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ActiveForm::end();
    ?>
