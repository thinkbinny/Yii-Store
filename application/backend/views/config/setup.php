<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;
$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '系统设置', 'url' => 'javascript:void(0);'];
$this->params['breadcrumbs'][] = $this->title;
/* @var $this yii\web\View */
/* @var $model backend\models\Config */
/* @var $form yii\widgets\ActiveForm */
$this->registerCss("
.layui-form-label{width:16%;}
.layui-form-item .layui-input-inline{width:500px;}
");
$this->registerJs("
layui.use('form', function(){
  var form = layui.form;
 form.render();
});
");
$this->registerJs("

",\yii\web\View::POS_END);
?>
<div class="layuimini-container">
    <div class="layuimini-main">
        <?=$this->render('_tab_menu');?>

        <div class="layui-main" style="padding-top: 20px;">
        <?=$this->render('setup/_'.$model->keyid,['model'=>$model]);?>
        </div>

</div>
</div>
