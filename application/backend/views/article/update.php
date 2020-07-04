<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\Admin */


$this->title = '编辑内容';
$this->params['breadcrumbs'][] = ['label' => ' 内容管理', 'url' => ['article/index']];
$this->params['breadcrumbs'][] = $this->title;
?>



    <?= $this->render('_form', [
        'model'=>$model,
    ]) ?>

