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

        echo Html::a('<span class="far fa-share-square"></span>&nbsp;还原','javascript:;',[
            'data-name'     => 'is_delete',
            'data-value'    => '0',
            'data-url'      => Url::to(['restore']),
            'data-form'     => 'ids',
            'data-text'     => '还原',
            'data-icon'     => '1',
            'class'         => 'ajax-status-post confirm layui-btn layui-btn-primary ',
        ]);

        echo Html::a('&nbsp;永久删除','javascript:;',[
            'class'=>'ajax-delete confirm layui-btn layui-btn-primary layui-icon layui-icon-delete',
            'data-url'      => Url::to(['delete']),
            'data-form'     => 'ids',
        ]);

        ?>


    </div>
    <div class="page-filter pull-right layui-search-form">


    </div>
</div>

