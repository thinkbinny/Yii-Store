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
<div class="search-form">
    <div class="search-form-left">
        <?

        echo Html::a('&nbsp;添加素材','https://mp.weixin.qq.com',[
            'class'         => 'layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine',
            'target'=>'_blank'
        ]);
        echo Html::a('&nbsp;同步素材',['create'],[
            'class'         => 'layui-btn layui-btn-primary layui-icon layui-icon-refresh-1 ajax-iframe-popup',
            'data-iframe'   => "{width: '600px', height: '250px', title: '同步素材'}",
        ]);
        ?>
    </div>
    <!--搜索-->
    <div class="search-form-right">
    <?
        $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'options'=>['class'=>'layui-form'],
        ]);

            echo $form->field($model, 'msg_type',['options'=>['class'=>'search-select']])
                ->dropDownList($model->getMsgType(),['prompt' => '请选择类型'])->label(false);
            echo $form->field($model, 'name',['options'=>['class'=>'search-text']])
                ->textInput(['class'=>'layui-input','placeholder'=>'请输入素材名称'])->label(false);
            echo  Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'layui-btn']);/**/
        ActiveForm::end();
    ?>
    </div>
    <!--END 搜索-->
</div>


