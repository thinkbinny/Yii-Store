<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;
use yii\widgets\ActiveField;

/* @var $this yii\web\View */
/* @var $model backend\models\Links */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs("
function mySubmit(){ 
   $('.ajax-submit').click();
}
",\yii\web\View::POS_END);

?>


    <?php $form = ActiveForm::begin([
        'options'=>['class'=>'layui-form'],

    ]);

        echo $form->field($model, 'name')
            ->textInput(['maxlength' => true,'style'=>'width:300px;','placeholder'=>'请输入分组名称']);

        if(!$model->type){
            $model->type =key($model->getType());
        }

        echo $form->field($model, 'type')->radioList($model->getType(),[
            'item' => function($index, $label, $name, $checked, $value)
            {
                $checked=$checked?"checked":"";
                $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                return $return;
            }
        ]);
        if(empty($model->sort)){
            $model->sort = 50;
        }
        echo $form->field($model, 'sort')
            ->textInput(['maxlength' => true,'style'=>'width:150px;','placeholder'=>'请输入分组顺序']);

        $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
        echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ActiveForm::end();
    ?>
