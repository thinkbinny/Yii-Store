<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model backend\models\Links */

$this->title = '修改会员等级';
$this->params['breadcrumbs'][] = ['label' => '会员管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("
function mySubmit(){ 
   $('.ajax-submit').click();
}
",\yii\web\View::POS_END);
$this->registerCss("
.layui-form-select dl{max-height: 150px;}
")
?>
<?php

    $form = ActiveForm::begin([
        //'enableClientScript'=>false,
        'options'=>['class'=>'layui-form'],
    ]);
    $grade = $model->getGrade();
    if(empty($model->grade)){
        $model->grade = key($grade);
    }
    echo $form->field($model,'grade',[
        'template' => '
            {label}
            <div style="width:470px;" class="layui-input-inline">
            {input}
            </div>',
    ])
    ->radioList($grade,[
        'item' => function($index, $label, $name, $checked, $value)
        {
            $checked=$checked?"checked":"";
            $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
            return $return;
        }
    ]);
    /*
    echo $form->field($model, 'grade')
        ->dropDownList($grade, ['prompt' => '请选择等级']);

    echo $form->field($model,'remarks')
        ->textarea(['placeholder'=>'请输入管理员备注','maxlength' => true,'style'=>'width:380px;height:120px;']);*/

    $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
    echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ActiveForm::end();

?>
