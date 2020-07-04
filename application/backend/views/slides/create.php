<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Links */

$this->title = '添加幻灯片';
$this->params['breadcrumbs'][] = ['label' => '系统设置', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;
?>


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

