<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Menu;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理栏目';
$this->params['breadcrumbs'][] = ['label' => '内容管理', 'url' => ['article/index']];
$this->params['breadcrumbs'][] = $this->title;
?>





<div class="layuimini-container">
    <div class="layuimini-main">
    <?=$this->render('_tab_menu');?>
    <?= GridView::widget([
        'options'=>['class'=>'layui-form'],
        'tableOptions'=>['class'=>'layui-table'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items}',
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
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
                'attribute' => 'title',
                'format' => 'raw',
                'label' => '菜单名称',
            ],

            [
                'options'=>['width'=>150,],
                'attribute' => 'model_id',
                'format' => 'raw',
                'label' => '所属模型',
                'value' =>function($data){
                    $category = new \backend\models\Category();
                    return $category->getModelNameText($data['model_id']);
                }
            ],

            [
                'class' => 'backend\grid\ActionColumn',
                'options'=>['width'=>230,],
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{create} {update} {delete}',/*  {move} {merge} */
                'buttons' => [
                    'create' => function ($url, $model, $key) {
                        if($model['model_id'] == 3){
                            return null;
                        }else{
                        return Html::a('<span class="fa fa-plus"></span> 添加子菜单', ['create', 'pid' => $key], [
                            'title' => '添加子菜单',
                            'class' => 'btn btn-success btn-xs ajax-iframe-popup',
                            'data-iframe'   => "{width: '1000px', height: '90%', title: '添加子菜单',scrollbar:'Yes'}",
                        ]);
                        }
                    },
                    'move' => function ($url, $model, $key) {
                            return Html::a('<span class="fa fa-refresh"></span> 移动', ['create', 'pid' => $key], [
                                'title' => '移动',
                                'class' => 'btn btn-warning btn-xs'
                            ]);
                        },
                    'merge' => function ($url, $model, $key) {
                            return Html::a('<span class="fa fa-retweet"></span> 合并', ['create', 'pid' => $key], [
                                'title' => '合并',
                                'class' => 'btn btn-info btn-xs'
                            ]);
                        },
                ],
                'buttonOptions'=>[
                    'update'=>[
                        'class'=>'btn btn-primary btn-xs ajax-iframe-popup',
                        'data-iframe'   => "{width: '1000px', height: '90%', title: '更新栏目',scrollbar:'Yes'}",

                    ]
                ],
            ],
        ],
    ]);

    ?>
    </div>
</div>
