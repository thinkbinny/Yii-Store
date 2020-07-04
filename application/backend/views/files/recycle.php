<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '文件回收站';
$this->params['breadcrumbs'][] = ['label' => '文件库管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;
$this->params['thisUrl'] = 'files/recycle';
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
    <?=$this->render('_tab_menu_recycle');?>
        <div class="page-toolbar">
            <div class="layui-btn-group">

                <?php

                echo Html::a('<span class="far fa-share-square"></span>&nbsp;还原','javascript:;',[
                    'data-name'     => 'is_delete',
                    'data-value'    => '0',
                    'data-url'      => Url::to(['restore']),
                    'data-form'     => 'ids',
                    'data-text'     => '还原',
                    'data-icon'     => '1',
                    'class'         => 'ajax-status-post confirm layui-btn layui-btn-primary ',
                ]);

                echo Html::a('&nbsp;永久删除','javascript:;',[
                    'class'=>'ajax-delete confirm layui-btn layui-btn-primary layui-icon layui-icon-delete',
                    'data-url'      => Url::to(['delete']),
                    'data-form'     => 'ids',
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
                'options'=>['width'=>140,],
                'class' => 'backend\grid\ActionColumn',
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{restore} {delete}',
                'buttons' => [
                    'restore' => function ($url, $model, $key) {

                        /*$options = [
                            'title' => Yii::t('backend', 'Restore'),
                            'aria-label' => Yii::t('backend', 'Restore'),
                            'data-isMsg' => true,
                            'class' => 'btn btn-success btn-xs ajax-get',
                        ];*/
                        $options = [
                            'title'         => Yii::t('backend', 'Restore'),
                            'aria-label'    => Yii::t('backend', 'Restore'),
                            'data-id'       => $model->id,
                            'class'         => 'btn btn-success btn-xs ajax-status-post',
                            'data-text'     => '还原',
                            'data-value'    => 0,
                            'data-icon'     => 1,
                            'data-name'     => 'is_delete',
                            'data-url'      => $url,
                        ];
                        return Html::a('<span class="far fa-share-square"></span> '.Yii::t('backend', 'Restore'), 'javascript:void(0);', $options);
                    },
                ],

            ],
        ],
    ]);
   ?>
    </div>
</div>
