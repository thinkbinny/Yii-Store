<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\MenuSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="page-toolbar">
    <div class="layui-btn-group">

        <?php
        echo Html::a('&nbsp;添加',['create'],[
            'class'         => 'layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine ajax-iframe-popup',
            'data-iframe'   => "{width: '750px', height: '410px', title: '添加菜单'}",
        ]);
        echo Html::a('&nbsp;启用','javascript:;',[
            'data-name'     => 'display',
            'data-value'    => '1',
            'data-url'      => Url::to(['status']),
            'data-form'     => 'ids',
            'class'         => 'ajax-status-post confirm layui-btn layui-btn-primary layui-icon layui-icon-play',
        ]);
        echo Html::a('&nbsp;禁用','javascript:;',[
            'data-name'     => 'display',
            'data-value'    => '0',
            'data-url'      => Url::to(['status']),
            'data-form'     => 'ids',
            'class'         => 'ajax-status-post confirm layui-btn layui-btn-primary layui-icon layui-icon-pause',
        ]);
       /* echo Html::a('&nbsp;删除','javascript:;',[
            'class'=>'ajax-delete confirm layui-btn layui-btn-primary layui-icon layui-icon-close',
            'data-url'      => Url::to(['delete']),
            'data-form'     => 'ids',
        ]);*/
        //<a data-href="/admin.php/user/index/resetpwd.html" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-refresh">&nbsp;重置密码</a>
        //<a data-href="/admin.php/user/index/del.html" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-close red">&nbsp;删除</a>
        ?>


    </div>
    <div class="page-filter pull-right">

        <?php
       /* $form = ActiveForm::begin([
            'fieldClass' => 'backend\widgets\ActiveSearch',
            'action' => ['index'],
            'method' => 'get',
            'options'=>['class'=>'layui-form-item'],
        ]);
            echo $form->field($model, 'name')->textInput(['placeholder' => '用户名、邮箱、手机、昵称'])->label(false);

            $text = Html::tag('i','',['class'=>'layui-icon layui-icon-search layuiadmin-button-btn']);
            echo Html::submitButton($text,['class'=>'layui-btn']);
        ActiveForm::end();*/
         ?>
    </div>
</div>



