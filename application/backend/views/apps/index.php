<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \backend\widgets\DisplayStyle;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '应用列表';
$this->params['breadcrumbs'][] = ['label' => '扩展管理', 'url' => 'javascript:;'];

$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("
   
");
?>

<div class="layuimini-container">
    <div class="layuimini-main">
    <?=$this->render('_search',['model'=>$searchModel]);?>

    <?php ActiveForm::begin(); ?>
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
                'class' => 'backend\grid\CheckBoxColumn',
                'attribute' => 'id',
                'options'=>['width'=>49,],
            ],
            [
                'options'=>['width'=>180,],
                'attribute' => 'app_id',
                'format' => 'raw',
                //'label' => '网站名称',
            ],
           /* [
                'options'=>['width'=>250,],
                'attribute' => 'app_secret',
                'format' => 'raw',

            ],*/
            [
                'attribute' => 'app_name',
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
                'options'=>['width'=>180,],
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
                'options'=>['width'=>140,],
                'class' => 'backend\grid\ActionColumn',
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{update} {delete}',//{view}
                'buttons' => [

                ],
                'buttonOptions'=>[
                    'update'=>[
                        'class'=>'btn btn-primary btn-xs ajax-iframe-popup',
                        'data-iframe'   => "{width: '750px', height: '450px', title: '更新应用'}",

                    ]
                ],
            ],
        ],
    ]);
    ActiveForm::end(); ?>
    </div>
</div>
