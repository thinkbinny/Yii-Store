<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\search\MenuSearch */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs("

");

?>
<div class="page-toolbar">
    <div class="layui-btn-group">

        <?php
        echo Html::a('&nbsp;添加',['create'],[
            'class'         => 'layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine ajax-iframe-popup',
            'data-iframe'   => "{width: '750px', height: '450px', title: '添加链接'}",
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
         echo Html::a('&nbsp;删除','javascript:;',[
             'class'=>'ajax-delete confirm layui-btn layui-btn-primary layui-icon layui-icon-close',
             'data-url'      => Url::to(['delete']),
             'data-form'     => 'ids',
         ]);
        //<a data-href="/admin.php/user/index/resetpwd.html" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-refresh">&nbsp;重置密码</a>
        //<a data-href="/admin.php/user/index/del.html" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-close red">&nbsp;删除</a>
        ?>


    </div>
    <div class="page-filter pull-right layui-search-form">


    </div>
</div>

