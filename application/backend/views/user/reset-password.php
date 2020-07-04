<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model backend\models\Links */

$this->title = '充值';
$this->params['breadcrumbs'][] = ['label' => '会员管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("

function mySubmit(){ 
    
      $('.ajax-submit').click();
   
}



",\yii\web\View::POS_END);
$this->registerCss("
.layui-form-select dl{max-height: 150px;}
")
?>



            <?php

            $form = ActiveForm::begin([
                'options'=>['class'=>'layui-form'],
            ]);


            echo $form->field($model, 'resetpassword')->passwordInput([
                'maxlength' => true,
                'style'=>'width:245px;',
                'placeholder'=>'请输入密码'
            ]);

            /*echo $form->field($model,'remarks')
                ->textarea(['placeholder'=>'请输入管理员备注','maxlength' => true,'style'=>'width:380px;height:120px;']);*/

            $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit1']);
            echo Html::tag('div',$Button,['class'=>'layui-hide']);
            ActiveForm::end();

            ?>




