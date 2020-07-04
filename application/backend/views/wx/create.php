<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Links */

$this->title = '添加友情';
$this->params['breadcrumbs'][] = ['label' => ' 系统设置', 'url' => ['config/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?=$this->render('_tab_menu');?>
<div class="layui-main">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
