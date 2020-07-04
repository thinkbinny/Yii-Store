<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Menu;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '优惠券管理';
$this->params['breadcrumbs'][] = ['label' => '营销管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;
?>





<div class="layuimini-container">
    <div class="layuimini-main">
    <?=$this->render('_tab_menu');?>
    <?=$this->render('_search',['model'=>$searchModel]);?>
    <?
    //try {
    echo GridView::widget([
        'options'=>['class'=>'layui-form'],
        'tableOptions'=>['class'=>'layui-table'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items}',
        'columns' => [
            [
                'class' => 'backend\grid\CheckBoxColumn',
                'attribute' => 'id',
                'options'=>['width'=>49,],
            ],
            [
                'options'=>['width'=>60,],
                'attribute' => 'id',
                'format' => 'raw',
                'label'=>'ID',
            ],

            'name',
            [
                'attribute' => 'type',
                'format' => 'raw',
                'options'=>['width'=>80,],
                'label'=>'类型',
                'value'=>function($model){
                    return $model->getTypeText();
                }
            ],
            [
                'attribute' => 'min_price',
                'format' => 'raw',
                'options'=>['width'=>120,],
            ],

            [
                'header'=>'优惠方式',
                'format' => 'raw',
                'options'=>['width'=>120,],
                'value'=>function($model){
                    if($model->type == 1){
                        return '立减 '.$model->price.' 元';
                    }else{
                        return '打 '.$model->discount.' 折';
                    }
                }
            ],
            [
                'header'=>'有效期',
                'format' => 'raw',
                'options'=>['width'=>150,],
                'value'=>function($model){
                    if($model->expire_type == 1){
                        return '领取 '.$model->expire_day.' 天内有效';
                    }else{
                        return date('Y-m-d',$model->start_time).'~'.date('Y-m-d',$model->end_time);
                    }
                }
            ],
            [
                'attribute' => 'amount',
                'format' => 'raw',
                'label'=>'总数量',
                'options'=>['width'=>90,],
            ],
            [
                'attribute' => 'receive',
                'format' => 'raw',
                'options'=>['width'=>90,],
            ],

            [
                'class' => 'backend\grid\SwitchColumn',
                'options'=>['width'=>90,],
                'header' => '是否显示',
                'attribute' => 'status',
            ],
           /* [
                'options'=>['width'=>155,],
                'attribute' => 'created_at',
                'format' => ['date','Y-m-d H:i:s'],
            ],*/

            [
                'class' => 'backend\grid\ActionColumn',
                'options'=>['width'=>135,],
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{update} {delete}',/*  {move} {merge} */
                'buttons' => [

                ],
                'buttonOptions'=>[
                    'update'=>[
                        'class'=>'btn btn-primary btn-xs ajax-iframe-popup',
                        'data-iframe'   => "{width: '750px', height: '90%', title: '更新优惠券',scrollbar:'Yse'}",
                    ]
                ],
            ],
        ],
    ]);
    /*}catch(\Exception $e){
        // todo
    }*/
    ?>
    </div>
</div>
