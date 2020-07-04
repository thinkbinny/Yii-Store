<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Menu;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '退货地址列表';
$this->params['breadcrumbs'][] = ['label' => '商城设置', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;
?>





<div class="layuimini-container">
    <div class="layuimini-main">
    <?=$this->render('_tab_menu');?>
    <?=$this->render('_search',['model'=>$searchModel]);?>
    <?= GridView::widget([
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
                'options'=>['width'=>80,],
                'attribute' => 'id',
                'label' => 'ID',
            ],
            [
                'options'=>['width'=>120,],
                'attribute' => 'name',
                'format' => 'raw',

            ],
            [
                'options'=>['width'=>120,],
                'attribute' => 'phone',
                'format' => 'raw',

            ],
            [
                'attribute' => 'detail',
                'format' => 'raw',

            ],

            [
                'options'=>['width'=>90,],
                "contentOptions" => ['style' => 'text-align:center;'],
                'attribute' => 'sort',

            ],
            [
                'class' => 'backend\grid\SwitchColumn',
                'options'=>['width'=>90,],
                'header' => '是否显示',
                'attribute' => 'status',
            ],

            [
                'options'=>['width'=>180,],
                'attribute' => 'updated_at',
                'format' => ['date','Y-m-d H:i:s'],
                'label' => '更新时间',

            ],


            [
                'class' => 'backend\grid\ActionColumn',
                'options'=>['width'=>145,],
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{update} {delete}',/*  {move} {merge} */
                'buttons' => [

                ],
                'buttonOptions'=>[
                    'update'=>[
                        'class'=>'btn btn-primary btn-xs ajax-iframe-popup',
                        'data-iframe'   => "{width: '650px', height: '400px', title: '更新退货地址'}"
                    ]
                ],
            ],
        ],
    ]);

    ?>
    </div>
</div>
