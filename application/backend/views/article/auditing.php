<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\bootstrap\Nav;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['thisUrl']='article/auditing';
$this->title = '待审核';
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


                echo Html::a('&nbsp;审核','javascript:;',[
                    'data-name'     => 'status',
                    'data-value'    => '1',
                    'data-url'      => Url::to(['restore','val'=>1]),
                    'data-form'     => 'ids',
                    'class'         => 'ajax-status-post confirm layui-btn layui-btn-primary layui-icon layui-icon-ok',
                ]);

                echo Html::a('&nbsp;删除','javascript:;',[
                    'class'=>'ajax-delete confirm layui-btn layui-btn-primary layui-icon layui-icon-close',
                    'data-url'      => Url::to(['delete']),
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
                        'attribute' => 'created_at',
                        'label' => '发布时间',
                        'format' => 'raw',
                        'options'=>['width'=>175,],
                        'value' => function ($data) {
                            return date('Y-m-d H:i:s',$data->created_at);
                        }
                    ],

                    [
                        'class' => 'backend\grid\ActionColumn',
                        'options'=>['width'=>200,],
                        'header' => Yii::t('backend', 'Operate'),
                        'template' => '{update} {auditing} {delete}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                $url = \yii\helpers\Url::to(['update','category_id'=>$model->category_id,'id'=>$model->id]);
                                $options = [
                                    'title' => Yii::t('yii', 'Update'),
                                    'aria-label' => Yii::t('yii', 'Update'),
                                    'data-pjax' => '0',
                                    'class' => 'btn btn-primary btn-xs',
                                ];
                                return Html::a('<span class="fa fa-edit"></span> '.Yii::t('yii', 'Update'), $url, $options);
                            },
                            'auditing' => function ($url, $model, $key) {
                                $url = \yii\helpers\Url::to(['restore','id'=>$model->id]);
                                $options = [
                                    'title' => Yii::t('backend', 'Auditing'),
                                    'aria-label' => Yii::t('backend', 'Auditing'),
                                    'data-pjax' => '0',
                                    'class' => 'btn btn-info btn-xs',
                                ];
                                return Html::a('<span class="fa fa-check-square-o"></span> '.Yii::t('backend', 'Auditing'), $url, $options);
                            },

                        ],

                    ],
                ],
            ]); ?>
</div>
</div>

