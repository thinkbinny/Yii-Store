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
        echo Html::button('启用',
            [
                'class'=>'layui-btn ajax-post confirm',
                'target-form'=>'ids',
                'data-url'=>Url::to(['changestatus','val'=>1]),
            ]
        );
        echo Html::button('禁止',
            [
                'class'=>'layui-btn layui-btn-normal ajax-post confirm',
                'target-form'=>'ids',
                'data-url'=>Url::to(['changestatus','val'=>0]),
            ]
        );
        /*echo Html::button('删除',
            [
                'class'=>'layui-btn layui-btn-danger ajax-post confirm',
                'target-form'=>'ids',
                'data-url'=>Url::to(['delete']),
            ]
        );*/
        ?>
    </div>
    <!--搜索-->

    <!--END 搜索-->
</div>


