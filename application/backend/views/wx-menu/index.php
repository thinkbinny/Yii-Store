<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '微信菜单';
$this->params['breadcrumbs'][] = ['label' => '微信配置', 'url' => ['wx/config']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("

");

?>

<div class="layuimini-container">
    <div class="layuimini-main">
        <?=$this->render('_tab_menu');?>
        <div class="page-toolbar">
            <div class="layui-btn-group">

                <?php
                echo Html::a('&nbsp;添加',['create'],[
                    'class'         => 'layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine ajax-iframe-popup',
                    'data-iframe'   => "{width: '1150px', height: '570px', title: '添加菜单'}",
                ]);
                echo Html::a('&nbsp;生成公众号菜单','javascript:;',[
                    'data-url'      => Url::to(['generate']),
                    'class'         => 'layui-btn layui-btn-primary layui-icon layui-icon-menu-fill ajax-get confirm',
                ]);

                ?>


            </div>
            <div class="page-filter pull-right layui-search-form">

            </div>
        </div>
<?

    echo GridView::widget([
        'options'=>['class'=>'layui-form'],
        'tableOptions'=>['class'=>'layui-table'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items}',
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'sort',
            [
                'class' => 'backend\grid\SortColumn',
                'options'=>['width'=>60,],
                'attribute' => 'sort',
                'header' => '排序',
            ],
            [
                'attribute' => 'id',
                'options'=>['width'=>60,],
                'label' => 'ID',
            ],
            //'pid',
            //'name',
            [
                'options'=>['width'=>250,],
                'attribute' => 'name',
                'format' => 'raw',
                'label' => '菜单名称',
            ],
            [
                'options'=>['width'=>120,],
                'attribute' => 'type',
                'label' => '菜单类型',
                'format' => 'raw',
                'value' => function($data){
                    $model = new \backend\models\WxMenu();
                    return $model->getTypeText($data['type']);
                }
            ],
            [
               // 'options'=>['width'=>220,],
                'attribute' => 'url',
                'format' => 'raw',
                'value'=>function($data){
                    return Html::input('text','url',$data['url'],['class'=>'layui-input']);
                    //return \common\components\Func::msubstr($data['url'],0,50,'utf-8',false);
                }
            ],

            [
                'class' => 'backend\grid\SwitchColumn',
                'options'=>['width'=>90,],
                'header' => '是否启用',
                'attribute' => 'display',
            ],

       /*     [
                'options'=>['width'=>150,],
                'class' => 'backend\grid\ActionColumn',
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{update} {delete}',// {delete}
                'buttons' => [

                ],
                'buttonOptions'=>[
                    'update'=>[
                        'class'=>'btn btn-primary btn-xs ajax-iframe-popup',
                        'data-iframe'   => "{width: '750px', height: '450px', title: '更新链接'}",

                    ]
                ],
            ],*/


            [
                'class' => 'backend\grid\ActionColumn',
                'options'=>['width'=>230,],
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{create} {update} {delete}',
                'buttons' => [
                    'create' => function ($url, $model, $key) {
                        if($model['pid']==0){
                        return Html::a('<span class="fa fa-plus"></span> 添加子菜单', ['create', 'pid' => $key], [
                            'title' => '添加子菜单',
                            'class' => 'btn btn-success btn-xs ajax-iframe-popup',
                            'data-iframe'   => "{width: '1150px', height: '570px', title: '添加子菜单'}",
                        ]);
                        }

                    },
                ],
                'buttonOptions'=>[
                   'update'=>[
                       'class'         => 'btn btn-primary btn-xs ajax-iframe-popup',
                       'data-iframe'   => "{width: '1150px', height: '570px', title: '更新菜单'}",
                   ]
                ]
            ],
        ],
    ]);




?>
</div>
</div>

