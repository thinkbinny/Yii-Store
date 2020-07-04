<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Menu */

$this->title = '添加属性';
$this->params['breadcrumbs'][] = '模型管理';
$this->params['breadcrumbs'][] = '属性列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-create">

    <?=$this->render('_tab_menu',['model' => $model]);?>

    <?= $this->render('_form', [
        'model' => $model,
       // 'treeArr' => $treeArr,
    ]) ?>

</div>
