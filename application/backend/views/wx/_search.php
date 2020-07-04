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

        echo Html::a('&nbsp;移除黑名单','javascript:;',[
            'data-name'     => 'status',
            'data-value'    => '1',
            'data-url'      => Url::to(['status']),
            'data-form'     => 'ids',
            'class'         => 'ajax-status-post confirm layui-btn layui-btn-primary layui-icon layui-icon-play',
        ]);
        echo Html::a('&nbsp;加入黑名单','javascript:;',[
            'data-name'     => 'status',
            'data-value'    => '0',
            'data-url'      => Url::to(['status']),
            'data-form'     => 'ids',
            'data-alert'    => '加入黑名单后，你将无法接收该用户发来的消息，且该用户无法接收公众号发出的消息。<br> 确认加入黑名单？',
            'class'         => 'ajax-status-post confirm layui-btn layui-btn-primary layui-icon layui-icon-pause',
        ]);
        echo Html::a('&nbsp;同步粉丝','javascript:;',[
            'data-url'      => Url::to(['status']),
            'data-form'     => 'ids',
            'class'         => 'ajax-delete confirm layui-btn layui-btn-primary layui-icon layui-icon-refresh',
        ]);
        echo Html::a('&nbsp;同步更新信息','javascript:;',[
            'data-url'      => Url::to(['status']),
            'data-form'     => 'ids',
            'class'         => 'ajax-delete confirm layui-btn layui-btn-primary layui-icon layui-icon-refresh-1',
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


            echo $form->field($model, 'subscribe',['options'=>['class'=>'layui-input-inline']])
                ->dropDownList($model->getSubscribe(),['prompt' => '是否关注'])->label(false);

            echo $form->field($model, 'nickname',['options'=>['class'=>'layui-input-inline','style'=>'width: 200px;']])
                ->textInput(['class'=>'layui-input','placeholder'=>'请输入微信昵称','id'=>'TitleSearch'])->label(false);
            $text = Html::tag('i','',['class'=>'layui-icon layui-icon-search layuiadmin-button-btn']);
            echo Html::submitButton($text,['class'=>'layui-btn']);
        ActiveForm::end();
    ?>
    </div>
    <!--END 搜索-->
</div>


