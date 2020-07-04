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

            echo $form->field($model, 'pid')->dropDownList([0 => '一级菜单']+$treeArr, ['encode' => false]);
            echo $form->field($model, 'name')
                ->textInput(['maxlength' => true,'style'=>'width:300px;'])
                ->hint('');//重复请刷新页面 （此参数为系统自动生成）

        echo $form->field($model, 'desc')->textarea(['maxlength' => true,'style'=>'width:550px;height:100px;']);


        $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
        echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ActiveForm::end();
    ?>
