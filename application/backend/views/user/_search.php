<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
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
$this->registerCss("
    .search-form{width: auto;}
    .search-form .search-form-left{float: left;height: 50px;line-height: 40px;}
    .search-form .search-form-right{float: right;height: 50px;line-height: 40px;}
    .search-form .search-select,.search-form .search-text{float: left;padding-top: 3px;}
    .search-form .search-form-right .search-select{width:120px;}
    .search-form .search-form-right .search-text{width:300px;}
");
?>
<div class="page-toolbar">
    <div class="layui-btn-group">
        <?
        echo Html::a('&nbsp;添加',['create'],[
            'class'         => 'layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine ajax-iframe-popup',
            'data-iframe'   => "{width: '700px', height: '220px', title: '添加链接'}",
        ]);
        echo Html::a('&nbsp;启用','javascript:;',[
            'data-name'     => 'status',
            'data-value'    => '1',
            'data-url'      => Url::to(['status']),
            'data-form'     => 'ids',
            'class'         => 'ajax-status-post confirm layui-btn layui-btn-primary layui-icon layui-icon-play',
        ]);
        echo Html::a('&nbsp;禁用','javascript:;',[
            'data-name'     => 'status',
            'data-value'    => '0',
            'data-url'      => Url::to(['status']),
            'data-form'     => 'ids',
            'class'         => 'ajax-status-post confirm layui-btn layui-btn-primary layui-icon layui-icon-pause',
        ]);
        ?>
    </div>
    <!--搜索-->
    <div class="page-filter pull-right layui-search-form">
    <?
    $form = ActiveForm::begin([
        'fieldClass' => 'backend\widgets\ActiveSearch',
        'action' => ['index'],
        'method' => 'get',
        'options'=>['class'=>'layui-form'],
    ]);
        $searchtype = [
                'uid'   => '用户UID',
            'nickname'  => '用户昵称',
            'username'  => '用户名',
            'email'     => '电子邮箱',
            'mobile'    => '手机号码',
        ];
        $placeholder = '请输入' . $searchtype[$model->searchtype];
            echo $form->field($model, 'status')
                ->dropDownList($model->getStatus(),['prompt' => '状态'])->label(false);
            echo $form->field($model, 'searchtype')
                ->dropDownList($searchtype,['lay-filter'=>'searchtype'])->label(false);
            echo $form->field($model, 'title',['options'=>['class'=>'layui-input-inline','style'=>'width: 300px;']])
                ->textInput(['class'=>'layui-input','placeholder'=>$placeholder,'id'=>'TitleSearch'])->label(false);
            echo  Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'layui-btn']);
        ActiveForm::end();
    ?>
    </div>
    <!--END 搜索-->
</div>


