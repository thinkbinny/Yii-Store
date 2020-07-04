<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;



/* @var $this yii\web\View */
/* @var $model backend\models\Links */

$this->title = '物流发货';
$this->params['breadcrumbs'][] = ['label' => '订单管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;
$js = <<<JS

function mySubmit(){ 
   $('.ajax-submit').click();
}

JS;

$this->registerJs($js,\yii\web\View::POS_END);
$css = <<<CSS
.layui-form-select dl{max-height:150px !important;}
CSS;
$this->registerCss($css);
$form = ActiveForm::begin([
    'options'=>['class'=>'layui-form'],

]);
$text = '可在 '.Html::a('物流公司列表',['logistics/index'],['style'=>'color:#0e90d2','target'=>'_blank']).' 中设置';
echo $form->field($model, 'shipping_code')
    ->dropDownList($model->shippingCompanyText,['encode' => false,'prompt' => '请选择'])
    ->width(280)
    ->hint($text);


echo $form->field($model, 'shipping_sn')
    ->textInput(['maxlength' => 50,'style'=>'width:280px;','class'=>'layui-input']);


$Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
echo Html::tag('div',$Button,['class'=>'layui-hide']);
ActiveForm::end();
?>


