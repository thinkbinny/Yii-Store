<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Links */

$this->title = '自动回复';
$this->params['breadcrumbs'][] = ['label' => '微信配置', 'url' => ['wx/config']];
$this->params['breadcrumbs'][] = $this->title;
?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
