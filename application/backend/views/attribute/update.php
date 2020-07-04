<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\Admin */

$this->title = '编辑属性';
$this->params['breadcrumbs'][] = '模型管理';
$this->params['breadcrumbs'][] = ['label' => ' 属性列表', ];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="admin-update">
    <?=$this->render('_tab_menu',['model' => $model]);?>
    <?= $this->render('_form', [
        'model' => $model,
        // 'treeArr' => $treeArr,
    ]) ?>

</div>
