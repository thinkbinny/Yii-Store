<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Admin */

$this->title = '修改个人信息';
$this->params['breadcrumbs'][] = ['label' => '我的面板', 'url' => ['index/index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['thisUrl'] = 'admin/editinfo';
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
                <label class="layui-form-label" >用户名</label>
                <div class="layui-form-mid layui-word-aux">
                   <?=$model->username?>
                </div>
            </div>
            <div class="layui-form-item field-admin-realname has-success">
                <label class="layui-form-label" >最后登录时间</label>
                <div class="layui-form-mid layui-word-aux">
                    <?=date('Y-m-d H:i:s',$model->last_login_time);?>
                </div>
            </div>
            <div class="layui-form-item field-admin-realname has-success">
                <label class="layui-form-label" for="admin-realname">最后登录IP</label>
                <div class="layui-form-mid layui-word-aux">
                    <?=long2ip($model->last_login_ip);?>
                </div>
            </div>


            <?
            echo $form->field($model, 'realname')->textInput(['maxlength' => true])->hint('真实姓名应该为2-20位之间')->width(300);
            echo $form->field($model, 'email')->textInput(['maxlength' => true])->hint('请输入Email')->width(300);
            $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn']);
            echo Html::tag('div',$Button,['class'=>'layui-form-button']);
            ActiveForm::end();

            ?>

        </div>
    </div>
</div>