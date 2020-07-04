<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Links */

$this->title = '修改金额';
$this->params['breadcrumbs'][] = ['label' => '订单管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>
