<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use \backend\widgets\DisplayStyle;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' 生成正则';
$this->params['breadcrumbs'][] = ['label' => ' 模型管理', 'url' => ['model/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?=$this->render('_tab_menu');?>
<div class="layui-main">

    <?
ActiveForm::begin(['enableClientScript'=>false]);

 ?>
    <div class="layui-form-item field-modelfield-tips has-success">
        <label class="layui-form-label" for="modelfield-tips">字段名称</label>
        <div style="width:700px;" class="layui-input-inline">
            <textarea id="attributeLabels-tips" class="layui-input" name="attributeLabels" style="padding: 10px;height: 200px;" placeholder="请输入字段名称" aria-invalid="false"><?=$model['attributeLabels']?></textarea>
            <div class="layui-help-block"></div></div>
        <div class="layui-form-mid layui-word-aux">一行一个字段。如：<br>title:标题<br>type:类型</div>
    </div>
    <div class="layui-form-item field-modelfield-tips has-success">
        <label class="layui-form-label" for="modelfield-tips">字段正则</label>
        <div style="width:700px;" class="layui-input-inline">
            <textarea id="rules-tips" class="layui-input" name="rules" style="padding: 10px;height: 300px;" placeholder="请输入字段正则" aria-invalid="false"><?=$model['rules']?></textarea>
            <div class="layui-help-block"></div>
        </div>
        <div class="layui-form-mid layui-word-aux">一行一个正则（第一个参数为字段）。如：<br>
            title|required|pattern:/^[a-z0-9\-_]+$/|max:255<br>
            {title,name}|required<br>
        </div>
    </div>
<?php
echo Html::tag('div',Html::submitButton('生成文件', ['class' => 'layui-btn']),['class'=>'layui-form-button']);
ActiveForm::end();
?>
</div>
