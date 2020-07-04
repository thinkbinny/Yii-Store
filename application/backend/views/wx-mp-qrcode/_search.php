<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\search\MenuSearch */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs("

");
$this->registerCss("

");
?>
<div class="page-toolbar">
    <div class="layui-btn-group">
        <?
        echo Html::a('&nbsp;添加二维码',['create'],[
            'class'         => 'layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine ajax-iframe-popup',
            'data-iframe'   => "{width: '600px', height: '330px', title: '添加二维码'}",
        ]);
        echo Html::a('&nbsp;删除','javascript:;',[
            'class'=>'ajax-delete confirm layui-btn layui-btn-primary layui-icon layui-icon-close',
            'data-url'      => Url::to(['delete']),
            'data-form'     => 'ids',
        ]);
        ?>
    </div>
    <!--搜索-->
    <div class="search-form-right">
    <?

    ?>
    </div>
    <!--END 搜索-->
</div>


