<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\search\MenuSearch */
/* @var $form yii\widgets\ActiveForm */

$this->registerCss("
   
");
?>
<div class="page-toolbar">
    <div class="layui-btn-group">
        <?
        $category_id = Yii::$app->request->get('category_id',0);
        if(empty($category_id)){
            echo Html::a('&nbsp;添加','javascript:popup.alert(\'请选择栏目类型\');',[
                'class'         => 'layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine',
            ]);
        }else{
            echo Html::a('&nbsp;添加',['create','category_id'=>$category_id],[
                'class'         => 'layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine ajax-iframe-popup',
                'data-iframe'   => "{width: '1100px', height: '90%', title: '添加内容',scrollbar:'Yse'}",
            ]);
        }

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

        echo Html::a('&nbsp;删除','javascript:;',[
            'class'=>'ajax-delete confirm layui-btn layui-btn-primary layui-icon layui-icon-close',
            'data-url'      => Url::to(['delete']),
            'data-form'     => 'ids',
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
            $data = $model->getStatus();
            unset($data[-1]);
            unset($data[2]);
            //隐藏字段 category_id
            //echo $form->field($model, 'category_id',['options'=>['style'=>'display: none;']])->hiddenInput()->label(false);
            echo Html::hiddenInput('category_id',$model->category_id);
            echo $form->field($model, 'status',['options'=>['class'=>'layui-input-inline']])
                ->dropDownList($data,['prompt' => '请选择'])->label(false);

            echo $form->field($model, 'title',['options'=>['class'=>'layui-input-inline','style'=>'width: 300px;']])
                ->textInput(['class'=>'layui-input','placeholder'=>'请输入搜索内容','id'=>'TitleSearch'])->label(false);

            $text = Html::tag('i','',['class'=>'layui-icon layui-icon-search layuiadmin-button-btn']);
            echo Html::submitButton($text,['class'=>'layui-btn']);

        ActiveForm::end();
    ?>
    </div>
    <!--END 搜索-->
</div>


