<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '选择门店';
$this->params['breadcrumbs'][] = ['label' => '门店管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("
 
    $('.layui-table tbody .select').click(function(){
        var _this = $(this);

        _this.addClass('active');
         _this.siblings().removeClass('active');
       
    })

");

$this->registerJs("
var index = parent.layer.getFrameIndex(window.name);
function submitSelect(name){ 

    var li   = $('.layui-table tbody .active');
    var attr = new Array()
    if(li.length === 0){
        layer.msg('请选择数据', {
          icon: 2,
          time: 1500 //2秒关闭（如果不配置，默认是3秒）
        });
        return false;
    }   
    
    var attr = new Array()
      li.each(function(){        
         attr.push({
         id:$(this).attr('data-id'),
         name:$(this).attr('data-name'),
         pic_url:$(this).attr('data-pic_url')
         })        
      }); 
      
    //console.log(attr)
  
    var iframe = window.parent.$('.layui-layer-iframe.win-popup')[0];
    if(iframe === undefined){ 
        var parentWindow = window.parent;
        eval(\"parentWindow.selectFile\"+name+\"(attr)\")
    }else{
        var frameId      = iframe.getElementsByTagName(\"iframe\")[0].id;console.log(frameId)
        var parentWindow = parent.document.getElementById(frameId).contentWindow;  
        eval(\"parentWindow.selectFile\"+name+\"(attr)\");       
    }
      //console.log(frameId)
     
            
    parent.layer.close(index);
}
",\yii\web\View::POS_END);
$this->registerCss("
    body{background:#fff;}
    .layui-table tbody tr{cursor: pointer;}
    .layui-table tbody .layui-icon-ok{display: none;}
    .layui-table tbody .active .layui-icon-ok{display: block;}
    .layui-form-radio{margin:0;padding-right:0;}
    .layui-form-radio > i{margin-right:0;}
");
?>

<div class="layuimini-container" style="border:none;">


    <div class="page-toolbar clearfix">
        <div class="layui-btn-group">


        </div>
        <div class="page-filter pull-right layui-search-form">
            <?php
            $form = ActiveForm::begin([
                'fieldClass' => 'backend\widgets\ActiveSearch',
                'action' => ['select'],
                'method' => 'get',
                'options'=>['class'=>'layui-form'],
            ]);

            $placeholder = '请输入门店名称/联系人';


            echo $form->field($searchModel, 'q',['options'=>['class'=>'layui-input-inline','style'=>'width: 300px;']])
                ->textInput(['placeholder' => $placeholder,'id'=>'TitleSearch'])->label(false);
            $text = Html::tag('i','',['class'=>'layui-icon layui-icon-search layuiadmin-button-btn']);
            echo Html::submitButton($text,['class'=>'layui-btn']);
            ActiveForm::end();
            ?>

        </div>
    </div>

    <?
    echo GridView::widget([
        'options'=>['class'=>'layui-form'],
        'tableOptions'=>['class'=>'layui-table'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'rowOptions'=>function($model){
            return [
                'class'=>'select',
                'data-name'=>$model->name,
                'data-pic_url'=>'',
                'data-id'=>$model->id,
            ];
        },
        'layout' => '{items} {pager}',//{summary}
        'columns' => [

            /*[

                'attribute' => 'id',
                'options'=>['width'=>90,],
            ],*/
            [
                'label'=>'选择',
                'attribute' => 'id',
                'format'    => 'raw',
                'contentOptions'=>['class'=>'text-center','style'=>'padding:0;'],
                'options'=>['width'=>60,],
                'value'=>function($model){
                    return Html::tag('i','',['class'=>'layui-icon layui-icon-ok']);
                }

            ],
            [
                'attribute' => 'name',
                'format' => 'raw',

            ],


            [
                'attribute' => 'linkman',
                'format' => 'raw',
                'options'=>['width'=>200,],

            ],
            [
                'attribute' => 'phone',
                'format' => 'raw',
                'options'=>['width'=>120,],

            ],
            /*[
                'attribute' => 'phone',
                'label'=>'门店地址',
                'format' => 'raw',
                'options'=>['width'=>200,],

            ],*/




        ],
    ]);
   ?>
    </div>

