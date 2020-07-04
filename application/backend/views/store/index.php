<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '门店列表';
$this->params['breadcrumbs'][] = ['label' => '门店管理', 'url' => 'javascript:;'];
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
            [
                'headerOptions' =>['style'=>'text-align: center'],
                'contentOptions' =>['style'=>'text-align: center'],
                'class' => 'backend\grid\CheckBoxColumn',
                'attribute' => 'id',
                'options'=>['width'=>49,],
            ],
            /*[

                'attribute' => 'id',
                'options'=>['width'=>90,],
            ],*/
            [
                'class' => 'backend\grid\SortColumn',
                'options'=>['width'=>70,],
                'attribute' => 'sort',
                'header' => '排序',
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',

            ],
            [
                'attribute' => 'logo_image_id',
                'format' => 'image',
                'options'=>['width'=>110,],
                'value' => function($model){
                    return \common\components\Func::getImageUrl($model->logo_image_id);
                }
            ],
            [
                'attribute' => 'shop_hours',
                'format' => 'raw',
                'options'=>['width'=>120,],

            ],
            [
                'attribute' => 'linkman',
                'format' => 'raw',
                'options'=>['width'=>90,],

            ],
            [
                'attribute' => 'phone',
                'format' => 'raw',
                'options'=>['width'=>90,],

            ],
            /*[
                'attribute' => 'phone',
                'label'=>'门店地址',
                'format' => 'raw',
                'options'=>['width'=>200,],

            ],*/
            [
                'headerOptions' =>['style'=>'text-align: center'],
                'contentOptions' =>['style'=>'text-align: center'],
                'attribute' => 'is_check',
                'format' => 'raw',
                'options'=>['width'=>90,],
                'value'=>function($model){
                    return $model->getIsCheckText();
                }
            ],
            [
                'headerOptions' =>['style'=>'text-align: center'],
                'contentOptions' =>['style'=>'text-align: center'],
                'class' => 'backend\grid\SwitchColumn',
                'options'=>['width'=>90,],
                'header' => '是否显示',
                'attribute' => 'status',
            ],
            [
                'options'=>['width'=>100,],
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
                        'data-iframe'   => "{width: '900px', height: '90%', title: '更新门店',scrollbar:'Yse'}",

                    ]
                ],
            ],
        ],
    ]);
   ?>
    </div>
</div>
