<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Model */
$this->title = '编辑模型';

$this->params['breadcrumbs'][] = $this->title;


?>


    <?= $this->render('_form', [
        'model' => $model,

    ]) ?>

