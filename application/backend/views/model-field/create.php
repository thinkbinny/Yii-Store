<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Model */

$this->title = '添加字段';
$this->params['breadcrumbs'][] = ['label' => ' 模型管理', 'url' => ['model/index']];
$this->params['breadcrumbs'][] = $this->title;

?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

