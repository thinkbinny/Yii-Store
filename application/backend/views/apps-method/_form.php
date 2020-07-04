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
        'fieldClass'        =>'backend\widgets\ActiveField',
    ]);

            echo $form->field($model, 'apps_menu_id')->dropDownList($model->getAppsMenuId(), ['encode' => false,'prompt' => '请选择']);

            echo $form->field($model, 'method')
                ->textInput(['maxlength' => true,'style'=>'width:300px;'])
                ->hint('（命名规则 如：api.mall.item.get）');//重复请刷新页面 （此参数为系统自动生成）
            echo $form->field($model, 'auth')->dropDownList($model->getAuth(), ['encode' => false]);
            echo $form->field($model, 'type')->dropDownList($model->getType(), ['encode' => false]);


        echo $form->field($model, 'description')->textarea(['maxlength' => true,'style'=>'width:550px;height:100px;']);


        $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
        echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ActiveForm::end();
    ?>
