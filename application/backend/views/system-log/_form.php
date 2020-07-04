<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs("
layui.use('form', function(){
  var form = layui.form;
 form.render();
});
");
?>

    <?php

    $form = ActiveForm::begin([
        //'enableClientScript'=>false,
        'options'=>['class'=>'layui-form'],
        'fieldClass'        =>'backend\widgets\ActiveField',
    ]);
        echo $form->field($model, 'name')
            ->textInput(['placeholder' => '请输入行为标识'])
            ->hint('（只能输入英文字母或下划线）')
            ->width(500);
        echo $form->field($model, 'title')
            ->textInput(['placeholder' => '请输入行为名称'])
            ->hint('')
            ->width(500);
        echo $form->field($model, 'type')
            ->dropDownList($model->getType());
        echo $form->field($model, 'remark')
            ->textarea(['style'=>'width: 500px;height: 120px;','placeholder' => '请输入行为描述']);

        //echo $form->field($model, 'rule')
        //    ->textarea(['style'=>'width: 500px;height: 120px;','placeholder' => '请输入行为规则，不写则只记录日志']);

        echo $form->field($model, 'log')
            ->textarea(['style'=>'width: 500px;height: 120px;','placeholder' => '请输入日志规则'])
            ->hint('（记录日志备注时按此规则来生成，支持[变量|函数]。<br>目前变量有：user,time,model,record,data;<br> 目前变量有：time_format,get_admin_username<br>等。）');
        echo $form->field($model, 'status')
            ->dropDownList($model->getStatus());
        $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn']);
        echo Html::tag('div',$Button,['class'=>'layui-form-button']);
    ActiveForm::end();

    ?>
<div style="" ></div>