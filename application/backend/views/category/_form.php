<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs("
function mySubmit(){ 
   $('.ajax-submit').click();
}
",\yii\web\View::POS_END);
$this->registerCss('

');
?>

<div class="menu-form">

    <?php $form = ActiveForm::begin([
        'options'=>['class'=>'layui-form'],

    ]); ?>

    <?= $form->field($model, 'pid')
        ->dropDownList([0 => '一级菜单']+$treeArr, ['encode' => false,'lay-filter'=>'formtype','prompt' => '请选择']) ?>

    <?= $form->field($model, 'title')
        ->textInput(['maxlength' => true])
        ->width(300)
        ->hint('名称不能为空') ?>
    <?= $form->field($model, 'subtitle')
        ->textInput(['maxlength' => true])
        ->hint('栏目副名称')
    ?>
    <?= $form->field($model, 'name')
        ->textInput(['maxlength' => true])
        ->hint('英文字母')
    ?>
    <?= $form->field($model, 'model_id')
        ->dropDownList($model->GetModelId(), ['encode' => false,'prompt' => '请选择']); //->hint('（如果是单页模型，此页面全部配置不生效）')
    ?>
    <?= $form->field($model, 'groups')->textarea(['style'=>'height:100px;width:500px;']) ?>
    <?
    if($model->allow_publish==''){ $model->allow_publish=1; }
    if($model->check==''){ $model->check=0; }
    ?>
    <?= $form->field($model, 'allow_publish')->radioList($model->getAllowpublish(),[
        'item' => function($index, $label, $name, $checked, $value)
        {
            $checked=$checked?"checked":"";
            $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
            return $return;
        }
    ])->hint('是否允许发布内容') ?>

    <?= $form->field($model, 'check')->radioList($model->getCheck(),[
        'item' => function($index, $label, $name, $checked, $value)
        {
            $checked=$checked?"checked":"";
            $return = "<input name=\"{$name}\" value=\"{$value}\" title=\"{$label}\" {$checked} type=\"radio\">";
            return $return;
        }
    ])->hint('在该分类下发布的内容是否需要审核') ?>


    <?= $form->field($model, 'display')->dropDownList($model->getDisplay())->hint('是否对用户可见，针对前台') ?>
    <?
    if(empty($model->sort)){ $model->sort=0; }
    if(empty($model->list_row)){ $model->list_row=10; }
    ?>
    <?= $form->field($model, 'sort')->textInput(['maxlength' => true])->hint('仅对当前层级分类有效') ?>

    <?= $form->field($model, 'list_row')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meta_title')->textInput()->width(500)->hint('SEO标题优化') ?>

    <?= $form->field($model, 'keywords')->textInput()->width(500)->hint('SEO关键词多个用英文","隔开') ?>

    <?= $form->field($model, 'description')->textarea(['style'=>'height:100px;width:500px;'])->hint('SEO描述') ?>

    <?= $form->field($model, 'template_index')->textInput()->width(350)->hint('前台会员管理文章频道 路径如：@templets\themes\template_index') ?>

    <?= $form->field($model, 'template_lists')->textInput()->width(350)->hint('前台会员管理文章列表 路径如：@templets\themes\template_lists') ?>

    <?= $form->field($model, 'template_detail')->textInput()->width(350)->hint('前台会员管理文章详情 路径如：@templets\themes\template_detail') ?>

    <?= $form->field($model, 'template_edit')->textInput()->width(350)->hint('前台会员管理文章编辑 路径如：@templets\themes\template_edit') ?>

    <?
    $Button =  Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'layui-btn ajax-submit']);
    echo Html::tag('div',$Button,['class'=>'layui-hide']);
    ?>

    <?php ActiveForm::end(); ?>

</div>
