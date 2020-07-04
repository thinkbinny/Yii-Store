<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '文件列表';
$this->params['breadcrumbs'][] = ['label' => '文件库管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("

   
   
    
");
$this->registerCss("
    .pic_url_name{
      color: #0e90d2;white-space:nowrap;overflow:hidden;max-width:275px;display:inline-block;text-overflow:ellipsis;
    }
");
?>

<div class="layuimini-container">
    <div class="layuimini-main">
    <?=$this->render('_tab_menu');?>
        <div class="page-toolbar">
            <div class="layui-btn-group">

                <?php

                echo Html::a('&nbsp;移入回收站','javascript:;',[
                    'class'=>'ajax-delete confirm layui-btn layui-btn-primary layui-icon layui-icon-close',
                    'data-url'      => Url::to(['del']),
                    'data-form'     => 'ids',
                    'data-text'     => '入回收站',
                ]);



                ?>


            </div>
            <div class="page-filter pull-right layui-search-form">


            </div>
        </div>

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
                'attribute' => 'name',
                'format' => 'raw',
                'value'=>function($model){
                    return Html::a($model->name,$model->save_url,['class'=>'pic_url_name','style'=>'','target'=>'_blank']);
                }
            ],
            [
                'attribute' => 'group_id',
                'options'=>['width'=>120,],
                'format' => 'raw',
                'value'=>function($model){
                    return $model->getGroupIdText();
                }
            ],

            [
                'attribute' => 'storage',
                'format' => 'raw',
                'options'=>['width'=>90,],

            ],
            /*[
                'attribute' => 'domain',
                'format' => 'raw',
                'options'=>['width'=>150,],

            ],*/
            [
                'attribute' => 'file_size',
                'format' => 'raw',
                'options'=>['width'=>120,],
                'value'=>function($model){
                    return \common\components\Func::sizeCount($model->file_size);
                }
            ],
            [
                'attribute' => 'file_type',
                'format' => 'raw',
                'options'=>['width'=>90,],

            ],
            [
                'attribute' => 'file_ext',
                'format' => 'raw',
                'options'=>['width'=>90,],

            ],
            [
                'options'=>['width'=>170,],
                'attribute' => 'created_at',
                'value'       =>  function($data){
                    return date('Y-m-d H:i:s',$data->created_at);
                }
            ],

            [
                'options'=>['width'=>120,],
                'class' => 'backend\grid\ActionColumn',
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{del}',// {delete}
                'buttons' => [
                    'del'=>function($url,$model){

                    $options = [
                        'title'         => '移入回收站',
                        'aria-label'    => '移入回收站',
                        'data-confirm'  => Yii::t('yii', '您确定要移入回收站?'),
                        'data-id'       => $model->id,
                        'data-text'     => '入回收站',
                        'class'         => 'btn btn-danger btn-xs ajax-delete confirm',
                    ];
                    return Html::a('<span class="fa fa-times"></span> 移入回收站', $url, $options);
                    }
                ],

            ],
        ],
    ]);
   ?>
    </div>
</div>
