<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '属性列表';
$this->params['breadcrumbs'][] = ['label' => '商品管理', 'url' => 'javascript:;'];
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
           /* [
                'class' => 'backend\grid\SortColumn',
                'options'=>['width'=>70,],
                'attribute' => 'sort',
                'header' => '排序',
            ],*/
            [
                'attribute' => 'name',
                'options'=>['width'=>120,],
                'format' => 'raw',
            ],
            [
                'attribute' => 'description',

                'format' => 'raw',


            ],
            [
                'attribute' => 'is_required',
                'format' => 'raw',
                'options'=>['width'=>110,],
                "contentOptions" => ['style' => 'text-align:center;'],
                "headerOptions" => ['style' => 'text-align:center;'],
                'value'=>function($model){
                    return $model->getIsRequiredText();
                }
            ],
            [
                'attribute' => 'type',
                'format' => 'raw',
                'options'=>['width'=>90,],
                "contentOptions" => ['style' => 'text-align:center;'],
                "headerOptions" => ['style' => 'text-align:center;'],
                'value'=>function($model){
                    return $model->getTypeText();
                }
            ],
            [
                'attribute' => 'model_type',
                'format' => 'raw',
                'options'=>['width'=>90,],
                "contentOptions" => ['style' => 'text-align:center;'],
                "headerOptions" => ['style' => 'text-align:center;'],
                'value' => function($model){
                    return $model->getModelTypeText();
                }
            ],


            [
                'class' => 'backend\grid\SwitchColumn',
                'options'=>['width'=>90,],
                'header' => '是否显示',
                'attribute' => 'status',
            ],
            [
                'options'=>['width'=>170,],
                'attribute' => 'updated_at',
                'format' => ['date','Y-MM-d H:i:s'],

            ],

            [
                'options'=>['width'=>200,],
                'class' => 'backend\grid\ActionColumn',
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{attrvalue} {update} {delete}',// {delete}
                'buttons' => [
                    'attrvalue'=>function ($url, $model, $key) {
                        $url = Url::to();
                        if($model->type==1){
                            return '';
                        }else{
                            return Html::a('<span class="fa fa-eye"></span>属性值', ['goods-attr-value/index','attr_id'=>$model->id], [
                                'title' => '属性值',
                                'class' => 'btn btn-info btn-xs btn-xs ajax-iframe-popup',
                                'data-iframe'   => "{skin:'layui-layer-background-none',shadeClose:true,width: '850px', height: '90%', title: '【{$model->name}】属性值',btn:false,scrollbar:'Yes'}",
                            ]);
                        }
                    }
                ],


                'buttonOptions'=>[
                    'update'=>[
                        'class'=>'btn btn-primary btn-xs ajax-iframe-popup',
                        'data-iframe'   => "{width: '650px', height: '500px', title: '更新属性'}",

                    ]
                ],
            ],
        ],
    ]);
   ?>
    </div>
</div>
