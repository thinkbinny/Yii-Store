<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model backend\models\Links */

$this->title = '充值';
$this->params['breadcrumbs'][] = ['label' => '会员管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("
var type = 1;
$(function(){
    $('.layui-tab-title li').click(function(){
        type = $(this).attr('data-type');
    });
})
function mySubmit(){ 
    if(type==1){
      $('.ajax-submit1').click();
    }else{
      $('.ajax-submit2').click();
    }
}


layui.use('element', function(){
  var $ = layui.jquery
  ,element = layui.element; //Tab的切换功能，切换事件监听等，需要依赖element模块
  
});

",\yii\web\View::POS_END);
$this->registerCss("
.layui-form-select dl{max-height: 150px;}
")
?>


<div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
    <ul class="layui-tab-title">
        <li data-type="1" class="layui-this">充值余额</li>
        <li data-type="2" >充值积分</li>

    </ul>
    <div class="layui-tab-content" style="height: 100px;">
        <div class="layui-tab-item layui-show">

            <?php

            $form = ActiveForm::begin([
                'options'=>['class'=>'layui-form'],
            ]);
            //
            $model->submit_type = 'money';
            echo $form->field($model,'submit_type',['options'=>['style'=>'display: none;']])->hiddenInput()->label(false);
            echo Html::hiddenInput('submit_type','money',['options'=>['style'=>'display: none']]);

            echo $form->field($model, 'money', [
                'template' => '
            {label}
            <div style="width:auto;padding: 9px 0px;" class="layui-input-inline">
            '.$model->money.'
            </div>',
            ])->label('当前余额');


            //echo $form->field() ;//充值方式 getRechargeType

            echo $form->field($model,'recharge_type')
                ->radioList($model->getRechargeType(),[
                    'item' => function($index, $label, $name, $checked, $value)
                    {
                        $checked=$checked?"checked":"";
                        $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                        return $return;
                    }
                ]);

            echo $form->field($model, 'recharge_money', [
                'template' => '
            {label}
            <div style="width:auto;" class="layui-input-inline">
            {input}
            </div>
            {hint}
            ',
            ])->textInput([
                'maxlength' => true,
                'style'=>'width:245px;',
                'placeholder'=>'请输入要变更的金额'
            ]);


            echo $form->field($model,'remarks')
                ->textarea(['placeholder'=>'请输入备注','maxlength' => true,'style'=>'width:380px;height:120px;']);

            $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit1']);
            echo Html::tag('div',$Button,['class'=>'layui-hide']);
            ActiveForm::end();

            ?>

        </div>
        <div class="layui-tab-item">


            <?php

            $form = ActiveForm::begin([
                'options'=>['class'=>'layui-form'],
            ]);
            //
            $model->submit_type = 'integral';
            echo $form->field($model,'submit_type',['options'=>['style'=>'display: none;']])->hiddenInput()->label(false);
            echo Html::hiddenInput('submit_type','integral',['options'=>['style'=>'display: none']]);

            echo $form->field($model, 'integral', [
                'template' => '
            {label}
            <div style="width:auto;padding: 9px 0px;" class="layui-input-inline">
            '.$model->integral.'
            </div>',
            ])->label('当前积分');


            //echo $form->field() ;//充值方式 getRechargeType

            echo $form->field($model,'recharge_type')
                ->radioList($model->getRechargeType(),[
                    'item' => function($index, $label, $name, $checked, $value)
                    {
                        $checked=$checked?"checked":"";
                        $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
                        return $return;
                    }
                ]);

            echo $form->field($model, 'recharge_integral', [
                'template' => '
            {label}
            <div style="width:auto;" class="layui-input-inline">
            {input}
            </div>
            {hint}
            ',
            ])->textInput([
                'maxlength' => true,
                'style'=>'width:245px;',
                'placeholder'=>'请输入要变更的数量'
            ]);


            echo $form->field($model,'remarks')
                ->textarea(['placeholder'=>'请输入备注','maxlength' => true,'style'=>'width:380px;height:120px;']);

            $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit2']);
            echo Html::tag('div',$Button,['class'=>'layui-hide']);
            ActiveForm::end();

            ?>


        </div>

    </div>
</div>




