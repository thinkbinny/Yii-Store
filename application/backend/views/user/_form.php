<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs("
function mySubmit(){ 
   $('.ajax-submit').click();
}
",\yii\web\View::POS_END);
?>

    <?php

    $form = ActiveForm::begin([
        //'enableClientScript'=>false,
        'options'=>['class'=>'layui-form'],

    ]);

        echo $form->field($model, 'username')
            ->textInput(['placeholder' => '请输入用户名称'])
            ->hint('（登陆账号,首字母必须是字母！）')
            ->width(280);
        //echo $form->field($model, 'email')->textInput(['placeholder' => '请输入电子邮箱'])->width(250);
        //echo $form->field($model, 'mobile')->textInput(['placeholder' => '请输入手机号码'])->width(250);
        echo $form->field($model, 'password')
            ->passwordInput(['placeholder' => '请输入登陆密码'])
            ->hint('（登陆密码必须是8-32位）')
            ->width(280);
        /*echo $form->field($model, 'nickname')
            ->textInput(['placeholder' => '请输入用户昵称'])
            ->hint('（用户显示昵称）')
            ->width(250);*/
        $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
        echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ActiveForm::end();

    ?>
