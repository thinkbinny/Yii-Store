<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Menu;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '评价列表';
$this->params['breadcrumbs'][] = ['label' => '商品管理', 'url' => 'javascript:;'];
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
                'options'=>['width'=>80,],
                'attribute' => 'id',
                'format' => 'raw',
                /*'value'=>function($model){
                    print_r($model);exit;
                }*/
            ],

            'goods_title',
            [
                'attribute' => 'from_uid',
                'format' => 'raw',
                'label' => '来源用户',
                'options'=>['width'=>120,],

            ],
            [
                'attribute' => 'score',
                'format' => 'raw',
                'options'=>['width'=>90,],
            ],

            [
                'attribute' => 'is_image',
                'format' => 'raw',
                'options'=>['width'=>90,],
            ],

            [
                'attribute' => 'sort',
                'format' => 'raw',
                'options'=>['width'=>90,],
            ],
            [
                'options'=>['width'=>90,],
                'attribute' => 'status',
                'format' => 'raw',
                "contentOptions" => ['style' => 'text-align:center;'],
                'value' => function($model){
                    return $model->getStatusText();
                },

            ],
            [
                'options'=>['width'=>155,],
                'attribute' => 'created_at',
                'format' => ['date','Y-m-d H:i:s'],
                'label' => '评论时间',

            ],

            [
                'class' => 'backend\grid\ActionColumn',
                'options'=>['width'=>135,],
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{update} {delete}',/*{copy}   {move} {merge} */
                'buttons' => [

                    'copy'=> function ($url, $model, $key) {
                        $options = [
                            'class' => 'btn btn-success btn-xs',
                        ];
                        return Html::a('<span class="far fa-copy"></span> 一键复制', $url, $options);
                    }
                ],
                'buttonOptions'=>[
                    'update'=>[
                        'class'=>'btn btn-primary btn-xs ajax-iframe-popup',
                        'data-iframe'   => "{width: '1100px', height: '98%', title: '更新商品',scrollbar:'Yse'}",
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
