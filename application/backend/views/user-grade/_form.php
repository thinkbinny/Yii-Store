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
$this->registerCss("
.layui-form-select dl{max-height: 225px;}
")
?>


    <?php $form = ActiveForm::begin([
        'options'=>['class'=>'layui-form'],

    ]);

        echo $form->field($model, 'name')
            ->textInput(['maxlength' => true,'style'=>'width:350px;','placeholder'=>'请输入等级名称'])
            ->hint('例如：大众会员、黄金会员、铂金会员、钻石会员');
        $data = range(0,50);
        echo $form->field($model, 'weight')->dropDownList($data, ['prompt' => '请选择等级权重'])
            ->hint('（会员等级的权重，数字越大 等级越高）');

        echo $form->field($model, 'upgrade',[
        'template' => '
            {label}
            <div style="width:auto;" class="layui-input-inline">
            <div class="input-group"><span class="input-group-addon">实际消费金额满</span>{input}<span class="input-group-addon">元</span></div>
            </div>
            {hint}
            ',
        ])->textInput([
            'style'=>'width:188px;'
        ])
            ->hint('用户的实际消费金额满n元后，自动升级，0代表不自动升级');

        echo $form->field($model, 'equity', [
            'template' => '
            {label}
            <div style="width:auto;" class="layui-input-inline">
            <div class="input-group"><span class="input-group-addon">折扣率</span>{input}<span class="input-group-addon">折</span></div>
            </div>
            {hint}
            ',
        ])->textInput([
            'maxlength' => true,
            'style'=>'width:245px;'
        ])
            ->hint('折扣率范围0-10，9.5代表9.5折，0代表不折扣');

        $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
        echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ActiveForm::end();
    ?>
