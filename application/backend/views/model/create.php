<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Model */

$this->title = '添加模型';

$this->params['breadcrumbs'][] = $this->title;

?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>


