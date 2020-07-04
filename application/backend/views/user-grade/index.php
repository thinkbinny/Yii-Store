<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '会员等级';
$this->params['breadcrumbs'][] = ['label' => '会员管理', 'url' => 'javascript:;'];
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
                'attribute' => 'name',
                'format' => 'raw',

            ],

            [
                'attribute' => 'weight',
                'format' => 'raw',
                'options'=>['width'=>150,],
            ],
            [
                'options'=>['width'=>150,],
                'attribute' => 'upgrade',
                'format' => 'raw',

            ],
            [
                'options'=>['width'=>150,],
                'attribute' => 'equity',
                'format' => 'raw',

            ],
            [
                'options'=>['width'=>180,],
                'attribute' => 'created_at',
                'value'       =>  function($data){
                    return date('Y-m-d H:i:s',$data->created_at);
                }
            ],

            [
                'class' => 'backend\grid\SwitchColumn',
                'options'=>['width'=>90,],
                'header' => '是否显示',
                'attribute' => 'status',
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
                        'data-iframe'   => "{width: '660px', height: '480px', title: '更新等级'}",

                    ]
                ],
            ],
        ],
    ]);
   ?>
    </div>
</div>
