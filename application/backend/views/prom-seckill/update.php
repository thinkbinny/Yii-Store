<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */

$this->title = '更新活动';
$this->params['breadcrumbs'][] = ['label' => '营销管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;
?>


    <?= $this->render('_form', [
        'model' => $model,
        'goods' => $goods,
        'skuList' => $skuList
    ]) ?>

