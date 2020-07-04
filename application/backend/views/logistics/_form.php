<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $form yii\widgets\ActiveForm */

$js = <<<JS
function mySubmit(){ 
   $('.ajax-submit').click();
}
JS;

$this->registerJs($js,\yii\web\View::POS_END);





?>

<div  style=" ">

    <?php $form = ActiveForm::begin([
        'options'=>['class'=>'layui-form'],

    ]);

    if($model->isNewRecord) {
        $model->sort = 50;
    }
    $text = '请对照 '.Html::a('物流公司编码表',['view'],['style'=>'color:#0e90d2','target'=>'_blank']).' 填写';

    echo  $form->field($model, 'name')
        ->textInput(['maxlength' => true])
        ->hint($text)
        ->width(300);

    echo  $form->field($model, 'code')
        ->textInput(['maxlength' => true])
        ->hint('用于快递100API查询物流信息，务必填写正确')
        ->width(300);
    echo  $form->field($model, 'sort')
        ->textInput(['maxlength' => true])
        ->hint('数字越小越靠前')
        ->width(150);


    $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
    echo Html::tag('div',$Button,['class'=>'layui-hide']);

     ActiveForm::end();
     ?>
</div>