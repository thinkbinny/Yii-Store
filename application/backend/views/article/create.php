<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Menu */

$this->title = '添加内容';
$this->params['breadcrumbs'][] = ['label' => ' 内容管理', 'url' => ['article/index']];

$this->params['breadcrumbs'][] = $this->title;

?>


    <?= $this->render('_form', [
        'model'=>$model,
    ]) ?>
