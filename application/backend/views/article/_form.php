<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs('
layui.use([\'layer\', \'form\'], function(){
  var layer = layui.layer,form = layui.form,laydate = layui.laydate;
   form.render();     
  
});

');
$this->registerJs("
function mySubmit(){ 
   $('.ajax-submit').click();
}
",\yii\web\View::POS_END);


$this->registerCss('

');
use yii\helpers\Url;
?>
<div class="article-form" style="">

    <?php
    $form = ActiveForm::begin([
        'options'=>['class'=>'layui-form'],

    ]);
    foreach ($model as $vals):
        $ListsField = $vals['field'];
        $item = $vals['item'];
        echo $this->render('_field',['form'=>$form,'field'=>$vals['field'],'model'=>$vals['item']]);

    endforeach;
    $Button =  Html::submitButton($item->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
    echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ActiveForm::end();
    ?>
</div>

