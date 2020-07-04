<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '消息列表';
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
                'attribute' => 'id',
                'format' => 'raw',
                'options'=>['width'=>100,],
            ],
            [
                'attribute' => 'uid',
                'format' => 'raw',
                'options'=>['width'=>100,],
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
            ],
            [
                'options'=>['width'=>100,],
                'attribute' => 'send_type',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->getSendTypeText($data->send_type);
                }
            ],
            [
                'options'=>['width'=>100,],
                'attribute' => 'type',
                'format' => 'raw',
                'value'  => function($data){
                    return $data->getTypeText($data->type);
                }
            ],

            [
                'options'=>['width'=>170,],
                'attribute' => 'send_time',
                'value'       =>  function($data){
                    return date('Y-m-d H:i:s',$data->send_time);
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

                'class' => 'backend\grid\SwitchColumn',
                'attribute' => 'status',
                //'label' => '是否显示',
                'options'=>['width'=>90,],

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
                        'data-iframe'   => "{width: '900px', height: '400px', title: '更新消息'}",

                    ]
                ],
            ],
        ],
    ]);
   ?>
    </div>
</div>
