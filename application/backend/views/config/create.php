<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Config */



$this->title = '添加配置';
$this->params['breadcrumbs'][] = ['label' => ' 系统设置', 'url' => ['config/index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="layui-main">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
