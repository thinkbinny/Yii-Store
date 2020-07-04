<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \backend\widgets\DisplayStyle;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '接口栏目';
$this->params['breadcrumbs'][] = ['label' => '扩展管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("


   
");
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
        'layout' => '{items}',//{summary}
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'sort',
                'label' => '排序',
                'format' => 'raw',
                'options'=>['width'=>60,],
                'value' => function($data) {
                    return Html::textInput('sort['.$data['id'].']', $data['sort'], ['class' => 'layui-input','style'=>'padding-left:0;height:25px;width:40px;text-align: center;']);
                }
            ],
            [
                'attribute' => 'id',
                'options'=>['width'=>80,],
                'label' => 'ID',
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'label' => '菜单名称',
            ],

            [
                'class' => 'backend\grid\SwitchColumn',
                'attribute' => 'status',
                'header' => '是否显示',
                'options'=>['width'=>90,],
            ],

            [
                'options'=>['width'=>230,],
                'class' => 'backend\grid\ActionColumn',
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{create} {update} {delete}',//{view}
                'buttons' => [
                    'create' => function ($url, $model, $key) {
                        return Html::a('<span class="fa fa-plus"></span> 添加子栏目', ['create', 'pid' => $key], [
                            'title' => '添加子栏目',
                            'class' => 'btn btn-success btn-xs ajax-iframe-popup',
                            'data-iframe'   => "{width: '700px', height: '350px', title: '添加子栏目'}",
                        ]);
                    },

                ],
                'buttonOptions'=>[
                    'update'=>[
                        'class'=>'btn btn-primary btn-xs ajax-iframe-popup',
                        'data-iframe'   => "{width: '700px', height: '350px', title: '更新栏目'}",

                    ]
                ],
            ],
        ],
    ]);

     ?>
    </div>
</div>
