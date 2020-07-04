<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '文件分组';
$this->params['breadcrumbs'][] = ['label' => '文件库管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("

    
");
?>

<div class="layuimini-container">
    <div class="layuimini-main">
    <?=$this->render('_tab_menu');?>


    <?
    echo GridView::widget([
        'options'=>['class'=>'layui-form'],
        'tableOptions'=>['class'=>'layui-table'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items} {pager}',//{summary}
        'columns' => [

            [

                'attribute' => 'id',
                'options'=>['width'=>90,],
            ],
            [
                'class' => 'backend\grid\SortColumn',
                'options'=>['width'=>90,],
                'attribute' => 'sort',
                'header' => '分组排序',
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',

            ],

            [
                'attribute' => 'type',
                'format' => 'raw',
                'options'=>['width'=>200,],
                'value' => function($model){
                    return $model->getTypeText().'（'.$model->type.'）';
                }
            ],


            [
                'options'=>['width'=>180,],
                'attribute' => 'created_at',
                'value'       =>  function($data){
                    return date('Y-m-d H:i:s',$data->created_at);
                }
            ],

            [
                'options'=>['width'=>145,],
                'class' => 'backend\grid\ActionColumn',
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{update} {delete}',// {delete}
                'buttons' => [

                ],
                'buttonOptions'=>[
                    'update'=>[
                        'class'=>'btn btn-primary btn-xs ajax-iframe-popup',
                        'data-iframe'   => "{width: '500px', height: '280px', title: '更新等级'}",

                    ]
                ],
            ],
        ],
    ]);
   ?>
    </div>
</div>
