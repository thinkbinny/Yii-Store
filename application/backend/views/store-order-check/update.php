<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Links */

$this->title = '修改门店';
$this->params['breadcrumbs'][] = ['label' => '门店管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>
