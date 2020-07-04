<?php
use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '菜单管理';
$this->params['breadcrumbs'][] = ['label' => '系统设置', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;

$this->params['thisUrl'] = 'menu/index';

$this->registerJs("

");

?>
<div class="layuimini-container">
    <div class="layuimini-main">

           <?
            echo $this->render('_tab_menu');
            echo $this->render('_search',['model'=>$searchModel]);
            echo GridView::widget([
                'options'=>['class'=>'layui-form'],
                'tableOptions'=>['class'=>'layui-table'],
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'filterPosition' => GridView::FILTER_POS_FOOTER,
                'layout' => '{items}',
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],
                    [
                        'class' => 'backend\grid\CheckBoxColumn',
                        'attribute' => 'id',
                        'options'=>['width'=>49,],
                    ],
                    [
                        'attribute' => 'id',
                        'options'=>['width'=>60,],
                        'label' => 'ID',
                    ],
                    [
                        'class' => 'backend\grid\SortColumn',
                        'options'=>['width'=>60,],
                        'attribute' => 'sort',
                        'header' => '排序',
                    ],
                    [
                        'attribute' => 'name',
                        'format' => 'raw',
                        'label' => '菜单名称',
                    ],
                    [
                        'options'=>['width'=>220,],
                        'attribute' => 'url',
                    ],
                    [
                        'options'=>['width'=>180,],
                        'attribute' => 'icon_style',
                        'label' => '图标样式',
                    ],


                    [
                        'class' => 'backend\grid\SwitchColumn',
                        'options'=>['width'=>90,],
                        'header' => '是否启用',
                        'attribute' => 'display',
                    ],
                    [
                        'class' => 'backend\grid\ActionColumn',
                        'options'=>['width'=>230,],
                        'header' => Yii::t('backend', 'Operate'),
                        'template' => '{create} {update} {delete}',
                        'buttons' => [
                            'create' => function ($url, $model, $key) {
                                return Html::a('<span class="fa fa-plus"></span> 添加子菜单', ['create', 'pid' => $key], [
                                    'title' => '添加子菜单',
                                    'class' => 'btn btn-success btn-xs ajax-iframe-popup',
                                    'data-iframe'   => "{width: '750px', height: '410px', title: '添加子菜单'}"
                                ]);
                            },
                        ],
                        'buttonOptions'=>[
                            'update'=>[
                                'class'=>'btn btn-primary btn-xs ajax-iframe-popup',
                                'data-iframe'   => "{width: '750px', height: '410px', title: '更新菜单'}"
                            ]
                        ],
                    ],
                ],
            ]);

            ?>

    </div>
</div>

