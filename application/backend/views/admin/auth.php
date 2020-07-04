<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

$this->title = '管理员角色';
$this->params['breadcrumbs'][] = ['label' => '管理员设置', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("
function mySubmit(){ 
   $('.layui-btn').click();
}
",\yii\web\View::POS_END);
?>

    <div class="layui-form" style="padding: 20px 30px;">
    <?php ActiveForm::begin([
        'options'=>['class'=>'layui-form-post'],
    ]); ?>
    <?

     echo Html::checkboxList('roleName',$adminGroups,$dataAll,[
         'item'=>function($index, $label, $name, $checked, $value)
         {
             $checked=$checked?"checked":"";
             $return = "<input lay-skin='primary' name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"checkbox\">";
             return $return;
         }
     ]);
    $Button =  Html::submitButton('保存', ['class' => 'layui-btn ajax-post','target-form'=>'layui-form-post']);
    echo Html::tag('div',$Button,['class'=>'layui-hide']);
 ActiveForm::end(); ?>
    </div>