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
    //监听表格复选框选择
    //全选
    var categoryName = '';
    form.on('select(searchtype)', function (data) {  
        categoryName = data.elem[data.elem.selectedIndex].text;  
        $('#TitleSearch').attr('placeholder','请输入'+categoryName);            
        form.render('select');   
    }); 
});
");

?>
<div class="page-toolbar">
    <div class="layui-btn-group">

        <?php

        echo Html::a('&nbsp;删除','javascript:;',[
            'class'=>'ajax-delete confirm layui-btn layui-btn-primary layui-icon layui-icon-delete',
            'data-url'      => Url::to(['delete']),
            'data-form'     => 'ids',
        ]);
        echo Html::a('&nbsp;清空所有',['clear'],[
            'class'=>'ajax-post confirm layui-btn layui-btn-primary layui-icon layui-icon-delete',

        ]);
        //<a data-href="/admin.php/user/index/resetpwd.html" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-refresh">&nbsp;重置密码</a>
        //<a data-href="/admin.php/user/index/del.html" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-close red">&nbsp;删除</a>
        ?>


    </div>
    <div class="page-filter pull-right layui-search-form">

        <?php


        /*

               $form = ActiveForm::begin([
                   'fieldClass' => 'backend\widgets\ActiveSearch',
                   'action' => ['index'],
                   'method' => 'get',
                   'options'=>['class'=>'layui-form'],
               ]);
               $searchtype = [
                   'title'     => '网站名称',
                   'url'       => '网站网址',
               ];



              $placeholder = '请输入' . $searchtype[$model->searchtype];
               echo $form->field($model, 'type',['options'=>['class'=>'layui-input-inline','style'=>'width: 120px;']])
                   ->dropDownList($model->getType(),['prompt' => '请选择类型'])->label(false);
               echo $form->field($model, 'searchtype')
                   ->dropDownList($searchtype,['lay-filter'=>'searchtype'])->label(false);//'prompt' => '搜索类型',
               echo $form->field($model, 'q',['options'=>['class'=>'layui-input-inline','style'=>'width: 300px;']])->textInput(['placeholder' => $placeholder,'id'=>'TitleSearch'])->label(false);

        $text = Html::tag('i','',['class'=>'layui-icon layui-icon-search layuiadmin-button-btn']);
        echo Html::submitButton($text,['class'=>'layui-btn']);
        ActiveForm::end();
        */
        ?>
    </div>
</div>

