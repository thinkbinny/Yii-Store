<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Admin */

$this->title = '修改密码';
$this->params['breadcrumbs'][] = ['label' => '我的面板', 'url' => ['index/index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['thisUrl'] = 'admin/resetpwd';
$this->registerCss("
.layui-main .layui-form-label{width: 150px;}
.layui-main .layui-form-button{padding-left:150px;}
");

?>
<div class="layuimini-container">
    <div class="layuimini-main">
        <?php
        echo $this->render('_tab_admin_menu');
        ?>
        <div class="layui-main">
            <?php

            $form = ActiveForm::begin([
                'options'=>['class'=>'layui-form'],
                'fieldClass'        =>'backend\widgets\ActiveField',
            ]);
            ?>

            <div class="layui-form-item field-admin-realname has-success">
                <label class="layui-form-label" for="admin-realname" >用户名</label>
                <div class="layui-form-mid layui-word-aux">
                   <?=$model->username?>&nbsp;&nbsp;(<?=$model->realname?>)
                </div>
            </div>
            <div class="layui-form-item field-admin-realname has-success">
                <label class="layui-form-label" for="admin-realname">E-mail</label>
                <div class="layui-form-mid layui-word-aux">
                    <?=$model->email?>
                </div>
            </div>



            <?
            echo $form->field($model, 'password')->passwordInput(['maxlength' => true,'placeholder'=>'请输入旧密码'])->label('旧密码')->width(300);
            echo $form->field($model, 'newpassword')->passwordInput(['maxlength' => true,'placeholder'=>'请输入新密码'])->width(300);
            echo $form->field($model, 'repassword')->passwordInput(['maxlength' => true,'placeholder'=>'请输入确认密码'])->width(300);
            $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn']);
            echo Html::tag('div',$Button,['class'=>'layui-form-button']);
            ActiveForm::end();

            ?>

        </div>
    </div>
</div>