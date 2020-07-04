<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
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

?>
<div class="page-toolbar clearfix">
    <div class="layui-btn-group ">




    </div>
    <div class="page-filter pull-right layui-search-form">
        <?php
        $form = ActiveForm::begin([
            'fieldClass' => 'backend\widgets\ActiveSearch',
            'action' => ['index'],
            'method' => 'get',
            'options'=>['class'=>'layui-form'],
        ]);

        echo $form->field($model, 'shop_id')
            ->widget('backend\components\select\Select',[
                'type'=>'text',
                'valueText'=>$model->getShopNameText(),
                'options'=>[
                    'width'=>'auto',
                    'title'=>'请选择门店',
                    'url' =>\yii\helpers\Url::toRoute(['store/select']),
                ]
            ]);
        $shop_id = Html::getInputId($model,'shop_id');
        $this->registerCss("
        .field-$shop_id{width:200px !important;}
        ");


        echo $form->field($model, 'realname',['options'=>['class'=>'layui-input-inline','style'=>'width: 300px;']])
            ->textInput(['placeholder' => '请输入核销员姓名'])
            ->label(false);
        $text = Html::tag('i','',['class'=>'layui-icon layui-icon-search layuiadmin-button-btn']);
        echo Html::submitButton($text,['class'=>'layui-btn']);
        ActiveForm::end();
        ?>

    </div>
</div>

