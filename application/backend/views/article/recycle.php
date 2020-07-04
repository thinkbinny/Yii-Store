<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\bootstrap\Nav;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['thisUrl']='article/recycle';
$this->title = '回收站';
$this->params['breadcrumbs'][] = ['label' => '内容管理', 'url' => ['article/index']];
$this->params['breadcrumbs'][] = $this->title;
$category_id        = Yii::$app->request->get('category_id');
$this->registerCss("


");
$this->registerJs("

   

   
");

?>

<div class="layuimini-container">
    <div class="layuimini-main">

        <div class="page-toolbar">
            <div class="layui-btn-group">

                <?php


                echo Html::a('<span class="far fa-share-square"></span>&nbsp;还原','javascript:;',[
                    'data-name'     => 'status',
                    'data-url'      => Url::to(['permit','val'=>1]),
                    'data-form'     => 'ids',
                    'class'         => 'ajax-status-post confirm layui-btn layui-btn-primary',

                ]);

                echo Html::a('&nbsp;清空','javascript:;',[
                    'class'=>'ajax-delete confirm layui-btn layui-btn-primary layui-icon layui-icon-delete',
                    'data-url'      => Url::to(['clear']),
                    'data-value'    => '1',
                    'data-form'     => 'ids',
                ]);

                ?>


            </div>
            <div class="page-filter pull-right layui-search-form">

            </div>
        </div>

            <?= GridView::widget([
                'options'=>['class'=>'layui-form'],
                'tableOptions'=>['class'=>'layui-table'],
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'filterPosition' => GridView::FILTER_POS_FOOTER,
                'layout' => '{items} {pager}',
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],                 return '<input type="checkbox" name="" lay-skin="primary">';
                    [
                        'class' => 'backend\grid\CheckBoxColumn',
                        'attribute' => 'id',
                        'options'=>['width'=>49,],
                    ],
                    [
                        'attribute'=>'id',
                        'label' => '编号',
                        'format' => 'raw',
                        'options'=>['width'=>70,],
                        'value'=>'id',
                    ],

                    [
                        'attribute' => 'title',
                        'label' => '标题',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return $data->title;
                        }
                    ],

                    [
                        'attribute' => 'category_id',
                        'label' => '栏目',
                        'format' => 'raw',
                        'options'=>['width'=>150,],
                        'value' => function ($data) {
                            return $data->getCategoryName($data->category_id);
                        }
                    ],
                    //'created_at:datetime',
                    [
                        'attribute' => 'updated_at',
                        'label' => '删除时间',
                        'format' => 'raw',
                        'options'=>['width'=>175,],
                        'value' => function ($data) {
                            return date('Y-m-d H:i:s',$data->updated_at);
                        }
                    ],

                    [
                        'class' => 'backend\grid\ActionColumn',
                        'options'=>['width'=>135,],
                        'header' => Yii::t('backend', 'Operate'),
                        'template' => '{restore} {delete}',
                        'buttons' => [
                            'restore' => function ($url, $model, $key) {
                                $url = \yii\helpers\Url::to(['permit','id'=>$model->id]);
                                $options = [
                                    'title' => Yii::t('backend', 'Restore'),
                                    'aria-label' => Yii::t('backend', 'Restore'),
                                    //'data-pjax' => '0',
                                    'class' => 'btn btn-success btn-xs ajax-get',
                                ];
                                return Html::a('<span class="far fa-share-square"></span> '.Yii::t('backend', 'Restore'), $url, $options);
                            },
                            'delete'=>function ($url, $model, $key) {
                                $url = \yii\helpers\Url::to(['clear','id'=>$model->id]);
                                $options = [
                                    'title' => Yii::t('yii', 'Delete'),
                                    'aria-label' => Yii::t('yii', 'Delete'),
                                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                    'class' => 'btn btn-danger btn-xs ajax-post confirm',
                                ];
                                return Html::a('<span class="fa fa-times"></span> '.Yii::t('yii', 'Delete'), $url, $options);
                            }
                        ],

                    ],
                ],
            ]); ?>

        </div>


</div>

