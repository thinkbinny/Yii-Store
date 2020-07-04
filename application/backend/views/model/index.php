<?php
use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '模型管理';
$this->params['breadcrumbs'][] = ['label' => '内容管理', 'url' => ['article/index']];
$this->params['breadcrumbs'][] = $this->title;

?>


<div class="layuimini-container">
    <div class="layuimini-main">
        <?=$this->render('_tab_menu',['model'=>$searchModel]);?>
    <?
ActiveForm::begin();
echo GridView::widget([
        'options'=>['class'=>'layui-form'],
        'tableOptions'=>['class'=>'layui-table'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items} {pager}',
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            [
                'class' => 'backend\grid\SortColumn',
                'options'=>['width'=>60,],
                'attribute' => 'sort',
                'header' => '排序',
            ],
            'id',
            'name',
            'title',
            'engine_type',
            [
              'options'=>['width'=>180,],
              'attribute'   =>  'created_at',
              'value'       =>  function($data){
                return date('Y-m-d H:i:s',$data->created_at);
              }
            ],
            [
                'options'=>['width'=>180,],
                'attribute'   =>  'updated_at',
                'value'       =>  function($data){
                    return date('Y-m-d H:i:s',$data->updated_at);
                }
            ],

            
            [
                'class' => 'backend\grid\SwitchColumn',
                'attribute' => 'status',

                //'label' => '是否启用',
                'options'=>['width'=>90,],

            ],
            [
                'class' => 'backend\grid\ActionColumn',
                'options'=>['width'=>165,],
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{view} {update}',//字段管理  {delete}
                'buttons' => [

                    'view' => function ($url, $model, $key) {
                        $options = [
                            'class'         =>'btn btn-info btn-xs',// ajax-iframe-popup
                            //'data-iframe'   => "{width: '1100px', height: '90%', title: '字段管理',scrollbar:'Yes'}",
                            ];
                        $url = \yii\helpers\Url::to(['model-field/index','model_id'=>$model->id]);
                        return Html::a('<span class="fa fa-eye"></span> 字段管理', $url, $options);

                    },
                ],
                'buttonOptions'=>[
                    'update'=>[
                        'class'=>'btn btn-primary btn-xs ajax-iframe-popup',
                        'data-iframe'   => "{width: '600px', height: '350px', title: '更新模型'}",
                    ],

                ]


            ],
        ],
    ]); ?>

<?php

ActiveForm::end();
?>
    </div>
</div>