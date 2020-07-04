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
                'class' => 'backend\grid\CheckBoxColumn',
                'attribute' => 'id',
                'options'=>['width'=>49,],
            ],

            [

                'attribute' => 'id',
                'options'=>['width'=>90,],
            ],

            [
                'attribute' => 'uid',
                'format' => ['image',[
                    'width'=>'50',
                    'height'=>'50'
                ]],
                'contentOptions'=>['class'=>'text-center',],
                'options'=>['width'=>90,],
                'label'=>'用户头像',
                'value' => function($model){
                    return \common\components\Func::getMemberInfo($model->uid,'headimgurl');
                }
            ],
            [
                'attribute' => 'uid',
                'format' => 'raw',
                'label'=>'用户昵称',
                'value' => function($model){
                    return \common\components\Func::getMemberInfo($model->uid,'nickname')."【UID:{$model->uid}】";
                }
            ],
            [
                'attribute' => 'shop_id',
                'format' => 'raw',
                'options'=>['width'=>200,],
                'value'=>function($model){
                    return $model->getShopNameText();
                }
            ],
           [
                'attribute' => 'realname',
                'format' => 'raw',
                'options'=>['width'=>90,],

            ],
            [
                'attribute' => 'mobile',
                'format' => 'raw',
                'options'=>['width'=>120,],

            ],
            /*[
                'attribute' => 'phone',
                'label'=>'门店地址',
                'format' => 'raw',
                'options'=>['width'=>200,],

            ],*/

            [
                'class' => 'backend\grid\SwitchColumn',
                'options'=>['width'=>90,],
                'header' => '是否显示',
                'attribute' => 'status',
            ],
            [
                'options'=>['width'=>170,],
                'attribute' => 'created_at',
                'format' => ['date', 'Y-MM-d H:i:s'],
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
                        'data-iframe'   => "{width: '650px', height: '430px', title: '更新店员'}",

                    ]
                ],
            ],
        ],
    ]);
   ?>
    </div>
</div>
