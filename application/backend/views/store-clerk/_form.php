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


        echo $form->field($model, 'uid')
            ->widget('backend\components\select\Select',['readonly'=>true])
            ->label('选择用户');
        echo $form->field($model, 'shop_id')
            ->widget('backend\components\select\Select',[
                'type'=>'text',
                'valueText'=>$model->getShopNameText(),
                'options'=>[
                    'title'=>'请选择门店',
                    'url' =>\yii\helpers\Url::toRoute(['store/select']),
                ]
            ]);
        echo $form->field($model, 'realname')
            ->textInput(['maxlength' => true,'style'=>'width:300px;','placeholder'=>'请输入门店联系人']);
        echo $form->field($model, 'mobile')
            ->textInput(['maxlength' => true,'style'=>'width:300px;','placeholder'=>'请输入门店联系电话']);




        $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
        echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ActiveForm::end();
    ?>
