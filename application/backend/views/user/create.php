<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Model */

$this->title = '添加用户';
$this->params['breadcrumbs'][] = ['label' => ' 用户管理', 'url' => ['user/index']];
$this->params['breadcrumbs'][] = $this->title;

?>



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>


