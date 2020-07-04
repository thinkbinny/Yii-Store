<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '友情链接';
$this->params['breadcrumbs'][] = ['label' => '系统设置', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("

    
");
?>

<div class="layuimini-container">
    <div class="layuimini-main">
    <?=$this->render('_tab_menu');?>
    <?=$this->render('_search',['model'=>$searchModel]);?>


    <?
    echo GridView::widget([
        'options'=>['class'=>'layui-form'],
        'tableOptions'=>['class'=>'layui-table'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items} {pager}',//{summary}
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'backend\grid\CheckBoxColumn',
                'attribute' => 'id',
                'options'=>['width'=>49,],
            ],
            [
                'class' => 'backend\grid\SortColumn',
                'options'=>['width'=>60,],
                'attribute' => 'sort',
                'header' => '排序',
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'label' => '网站名称',
            ],
           // 'url:url',
            [
                'attribute' => 'url',
                'format' => 'raw',
                'value' => function($data) {
                    return Html::a($data->url,$data->url,['target'=>'_blank']);
                }
            ],
            [
                'options'=>['width'=>150,],
                'attribute' => 'type',
                'format' => 'raw',
                'value'  => function($data){
                    return $data->getTypeText($data->type);
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
                        'data-iframe'   => "{width: '750px', height: '550px', title: '更新链接'}",

                    ]
                ],
            ],
        ],
    ]);
   ?>
    </div>
</div>
