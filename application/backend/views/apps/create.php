<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Links */

$this->title = '添加应用';
$this->params['breadcrumbs'][] = ['label' => '扩展管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = ['label' => '应用列表', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;
?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

