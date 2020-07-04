<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \backend\widgets\DisplayStyle;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '幻灯片管理';
$this->params['breadcrumbs'][] = ['label' => '系统设置', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("

   
");
?>
<div class="layuimini-container">
    <div class="layuimini-main">
    <?=$this->render('_tab_menu');?>
    <?=$this->render('_search',['model'=>$searchModel]);?>


    <?php
    echo GridView::widget([
        'options'=>['class'=>'layui-form'],
        'tableOptions'=>['class'=>'layui-table'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items}',//{summary}
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'backend\grid\CheckBoxColumn',
                'attribute' => 'id',
                'options'=>['width'=>49,],
            ],

            [
                'class' => 'backend\grid\SortColumn',
                'options'=>['width'=>60,],
                'attribute' => 'sort',
                'header' => '排序',
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
                //'label' => '网站名称',
            ],
            [
                'options'=>['width'=>120,],
                'attribute' => 'image_id',
                'format' => 'raw',
                'value'=>function($data){
                    $imageUrl = $data->getImageUrl();
                    if(empty($imageUrl))
                        return '未上传图片';
                    else
                        return Html::a('浏览图片',$imageUrl,['target'=>'_blank','class'=>'layui-btn layui-btn-xs']);
                }
            ],
            [
                'options'=>['width'=>120,],
                'attribute' => 'url',
                'format' => 'raw',
                'value' => function($data) {
                    if(empty($data->url))
                        return null;
                    else
                        return Html::a('浏览网址',$data->url,['target'=>'_blank','class'=>'layui-btn layui-btn-xs layui-btn-danger']);
                }
            ],
            [
                'options'=>['width'=>80,],
                'attribute' => 'type',
                'format' => 'raw',
                'value'  => function($data){
                    return $data->getTypeText($data->type);
                }
            ],
            [
                'options'=>['width'=>160,],
                'attribute' => 'created_at',
                'format' => ['date', 'Y-M-d H:i:s'],
            ],
            [
                'class' => 'backend\grid\SwitchColumn',
                'options'=>['width'=>90,],
                'header' => '是否显示',
                'attribute' => 'status',
            ],

            [
                'options'=>['width'=>150,],
                'class' => 'backend\grid\ActionColumn',
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{update} {delete}',// {delete}
                'buttons' => [

                ],
                'buttonOptions'=>[
                    'update'=>[
                        'class'=>'btn btn-primary btn-xs ajax-iframe-popup',
                        'data-iframe'   => "{width: '750px', height: '550px', title: '更新幻灯片'}",

                    ]
                ],
            ],
        ],
    ]);
 ?>
    </div>
</div>
