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
        echo Html::a('&nbsp;添加',['create'],[
            'class'         => 'layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine ajax-iframe-popup',
            'data-iframe'   => "{width: '650px', height: '430px', title: '添加店员'}",
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

        ?>


    </div>
    <div class="page-filter pull-right layui-search-form">
        <?php
        $form = ActiveForm::begin([
            'fieldClass' => 'backend\widgets\ActiveSearch',
            'action' => ['index'],
            'method' => 'get',
            'options'=>['class'=>'layui-form'],
        ]);

        echo $form->field($model, 'shop_id')
            ->widget('backend\components\select\Select',[
                'type'=>'text',
                'valueText'=>$model->getShopNameText(),
                'options'=>[
                    'width'=>'auto',
                    'title'=>'请选择门店',
                    'url' =>\yii\helpers\Url::toRoute(['store/select']),
                ]
            ]);
        $shop_id = Html::getInputId($model,'shop_id');
        $this->registerCss("
        .field-$shop_id{width:200px !important;}
        ");

        echo $form->field($model, 'status',['options'=>['class'=>'layui-input-inline','style'=>'width: 120px;']])
            ->dropDownList($model->getStatus(),['prompt' => '请选择'])->label(false);
        /*echo $form->field($model, 'shop_id')
            ->dropDownList([],['prompt' => '所属门店',])->label(false);//*/
        echo $form->field($model, 'q',['options'=>['class'=>'layui-input-inline','style'=>'width: 300px;']])
            ->textInput(['placeholder' => '请输入店员姓名/手机号','id'=>'TitleSearch'])
            ->label(false);
        $text = Html::tag('i','',['class'=>'layui-icon layui-icon-search layuiadmin-button-btn']);
        echo Html::submitButton($text,['class'=>'layui-btn']);
        ActiveForm::end();
        ?>

    </div>
</div>

