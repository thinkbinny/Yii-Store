<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $form yii\widgets\ActiveForm */
$css = <<<CSS
.layui-form-select dl{max-height: 250px;}
CSS;
$this->registerCss($css);
$this->registerJs("
function mySubmit(){ 
   $('.ajax-submit').click();
}
",\yii\web\View::POS_END);
$form = ActiveForm::begin([
    'options'=>['class'=>'layui-form'],
]);
echo $form->field($model, 'pid')->dropDownList([0 => '一级菜单']+$treeArr, ['encode' => false]);
echo $form->field($model, 'name')->textInput(['maxlength' => true])->width(200);
echo $form->field($model, 'url')->textInput(['maxlength' => true])->hint('格式: index/index&id=2&type=1')->width(350);
echo $form->field($model, 'icon_style')
    ->textInput(['maxlength' => true])
    ->hint('如：fa fa-user [ <a href="https://fontawesome.com/icons" target="_blank" style="text-decoration:underline;">官网</a> 、<a href="http://fa5.dashgame.com" target="_blank" style="text-decoration:underline;">中文演示</a> ]')
    ->width(200);

echo $form->field($model, 'display')->radioList($model->getDisplays(),[
    'item' => function($index, $label, $name, $checked, $value)
    {
        $checked=$checked?"checked":"";
        $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
        return $return;
    }
]);


$Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
echo Html::tag('div',$Button,['class'=>'layui-hide']);
ActiveForm::end();

?>
