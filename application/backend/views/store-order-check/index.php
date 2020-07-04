<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '核销记录列表';
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

                'attribute' => 'id',
                'options'=>['width'=>90,],
            ],

            [
                'attribute' => 'order_sn',
                'format' => 'raw',
                'options'=>['width'=>200,],

            ],
            [
                'attribute' => 'shop_id',
                'format' => 'raw',

                'value'=>function($model){
                    return $model->getShopNameText();
                }
            ],
            [
                'attribute' => 'uid',
                'format' => 'raw',
                'label'=>'核销用户',
                'options'=>['width'=>200,],
                'value' => function($model){
                    return \common\components\Func::getMemberInfo($model->uid,'nickname')."【UID:{$model->uid}】";
                }
            ],
           [
                'attribute' => 'realname',
                'format' => 'raw',
                'options'=>['width'=>150,],

            ],

            /*[
                'attribute' => 'phone',
                'label'=>'门店地址',
                'format' => 'raw',
                'options'=>['width'=>200,],

            ],*/


            [
                'options'=>['width'=>170,],
                'label'  =>  '核销时间',
                'attribute' => 'created_at',
                'format' => ['date', 'Y-MM-d H:i:s'],
            ],


        ],
    ]);
   ?>
    </div>
</div>
