<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \backend\widgets\DisplayStyle;
use yii\widgets\ActiveForm;
use yii\bootstrap\Nav;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
echo Nav::widget([

]);
$this->title = '素材列表';
$type =  Yii::$app->request->get('type','');
$this->registerJs("
    var index = parent.layer.getFrameIndex(window.name);
    $('.layui-table tr').click(function(){
        var val = $(this).attr('data-media_id');
        //alert(val);
        window.parent.wxSelectFile(val);
        //alert(index);
        parent.layer.close(index);
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
<style type="text/css">
    .layui-table tr{cursor: pointer;}
</style>



<div class="layui-main" style="width:auto;  ">

    <div class="search-form">
        <div class="search-form-left">

        </div>
        <!--搜索-->
        <div class="search-form-right">
            <?
            $form = ActiveForm::begin([
                'action' => ['lists','type'=>$type],
                'method' => 'get',
                'options'=>['class'=>'layui-form'],
            ]);
            if(empty($type)){
            echo $form->field($searchModel, 'msg_type',['options'=>['class'=>'search-select']])
                ->dropDownList($searchModel->getMsgType(),['prompt' => '请选择类型'])->label(false);
            }
            echo $form->field($searchModel, 'name',['options'=>['class'=>'search-text']])
                ->textInput(['class'=>'layui-input','placeholder'=>'请输入素材名称'])->label(false);
            echo  Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'layui-btn']);/**/
            ActiveForm::end();
            ?>
        </div>
        <!--END 搜索-->
    </div>
    <?
    if(empty($type)){
            echo GridView::widget([
                'options'=>['class'=>'layui-form'],
                'tableOptions'=>['class'=>'layui-table'],
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'filterPosition' => GridView::FILTER_POS_FOOTER,
                'layout' => '{items} {summary} {pager}',//{summary}
                'rowOptions' => function($model, $key, $index, $grid) {
                    return ['data-media_id' => $model->media_id];
                },
                'columns' => [
                    [
                        'options'=>['width'=>130,],
                        'attribute' => 'msg_type',
                        'format' => 'raw',
                        'value'  => function($data){
                            return $data->getMsgTypeText($data->msg_type);
                        }
                    ],
                    [
                        'attribute' => 'name',
                        'format' => 'raw',

                    ],
                ],
            ]);
    }else{

        echo GridView::widget([
            'options'=>['class'=>'layui-form'],
            'tableOptions'=>['class'=>'layui-table'],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'filterPosition' => GridView::FILTER_POS_FOOTER,
            'layout' => '{items}  {pager}',//{summary}
            'rowOptions' => function($model, $key, $index, $grid) {
                return ['data-media_id' => $model->media_id];
            },
            'columns' => [

                [
                    'attribute' => 'name',
                    'format' => 'raw',

                ],
            ],
        ]);


    }
   ?>

</div>
