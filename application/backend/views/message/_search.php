<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\search\MenuSearch */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs("
layui.use('form', function() {
    var form = layui.form;
    form.render();  
   
});
");
$this->registerCss("

");
?>



