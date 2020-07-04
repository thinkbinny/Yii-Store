<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \backend\widgets\DisplayStyle;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'API方法';
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
                'class' => 'backend\grid\SortColumn',
                'options'=>['width'=>60,],
                'attribute' => 'sort',
                'header' => '排序',
            ],
            [
                'attribute' => 'apps_menu_id',
                'options'=>['width'=>120,],
                'value'     => function($data){
                    return $data->getAppsMenuIdText();
                }
            ],
            [
                'attribute' => 'method',
                'format' => 'raw',
                //'label' => '菜单名称',
            ],
            [
                'options'   =>['width'=>100,],
                'attribute' => 'auth',
                'format'    => 'raw',
                'value'     => function($data){
                    return $data->getAuthText();
                }
            ],
            [
                'options'   =>['width'=>100,],
                'attribute' => 'type',
                'format'    => 'raw',
                'value'     => function($data){
                    return $data->getTypeText();
                }
            ],
            [
                'options'=>['width'=>170,],
                'attribute' => 'created_at',
                'value'       =>  function($data){
                    return date('Y-m-d H:i:s',$data->created_at);
                }
            ],
            [
                'options'=>['width'=>170,],
                'attribute' => 'updated_at',
                'value'       =>  function($data){
                    return date('Y-m-d H:i:s',$data->updated_at);
                }
            ],
            [
                'class' => 'backend\grid\SwitchColumn',
                'attribute' => 'status',
                'header' => '是否显示',
                'options'=>['width'=>90,],

            ],

            [
                'options'=>['width'=>150,],
                'class' => 'backend\grid\ActionColumn',
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{update} {delete}',//{view}
                'buttonOptions'=>[
                    'update'=>[
                        'class'=>'btn btn-primary btn-xs ajax-iframe-popup',
                        'data-iframe'   => "{width: '700px', height: '450px', title: '更新链接'}",

                    ]
                ],
            ],
        ],
    ]);
    ?>
</div>

</div>