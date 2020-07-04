<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Menu;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '分类管理';
$this->params['breadcrumbs'][] = ['label' => '商品管理', 'url' => 'javascript:;'];
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
                'options'=>['width'=>60,],
                'attribute' => 'sort',
                'label' => '排序',
                'format' => 'raw',
                'value' => function($data) {
                    return Html::textInput('sort['.$data['id'].']', $data['sort'], ['class' => 'layui-input','style'=>'padding-left:0;height:25px;width:40px;text-align: center;']);
                }
            ],
            [
                'options'=>['width'=>80,],
                'attribute' => 'id',
                'label' => 'ID',
            ],
            //'pid',
            //'name',
            [
                'attribute' => 'name',
                'format' => 'raw',
                'label' => '菜单名称',
            ],
            [
                'options'=>['width'=>180,],
                'attribute' => 'created_at',
                'format' => ['date','Y-m-d H:i:s'],
                'label' => '添加时间',

            ],
            [
                'class' => 'backend\grid\SwitchColumn',
                'options'=>['width'=>90,],
                'header' => '是否显示',
                'attribute' => 'status',
            ],


            [
                'class' => 'backend\grid\ActionColumn',
                'options'=>['width'=>230,],
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{create} {update} {delete}',/*  {move} {merge} */
                'buttons' => [
                    'create' => function ($url, $model, $key) {
                        if($model['parent_id'] == 0){
                            return Html::a('<span class="fa fa-plus"></span> 添加子分类', ['create', 'parent_id' => $key], [
                                'title' => '添加子菜单',
                                'class' => 'btn btn-success btn-xs ajax-iframe-popup',
                                'data-iframe'   => "{width: '700px', height: '420px', title: '添加子分类'}",
                            ]);
                        }else{
                            return '';
                        }
                    },


                ],
                'buttonOptions'=>[
                    'update'=>[
                        'class'=>'btn btn-primary btn-xs ajax-iframe-popup',
                        'data-iframe'   => "{width: '700px', height: '420px', title: '更新分类'}",

                    ]
                ],
            ],
        ],
    ]);

    ?>
    </div>
</div>
