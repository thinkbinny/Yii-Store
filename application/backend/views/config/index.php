<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use \backend\widgets\DisplayStyle;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;

$this->title = ' 配置管理';
$this->params['breadcrumbs'][] = ['label' => '系统设置', 'url' => ['config/index']];
//$this->params['breadcrumbs'][] = ['label' => ' 配置管理', 'url' => ['config/index','keyid'=>'all']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="layuimini-container">
  <div class="layuimini-main">
        <?=$this->render('_tab_menu');?>
        <div class="page-toolbar">
            <div class="layui-btn-group">

                <?php
                echo Html::a('&nbsp;添加',['create'],[
                    'class'         => 'layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine ajax-iframe-popup',
                    'data-iframe'   => "{width: '450px', height: '280px', title: '添加配置'}",
                ]);
                echo Html::a('&nbsp;启用','javascript:;',[
                    'data-name'     => 'status',
                    'data-value'    => '1',
                    'data-url'      => Url::to(['status']),
                    'data-form'     => 'ids',
                    'class'         => 'ajax-status-post confirm layui-btn layui-btn-primary layui-icon layui-icon-play',
                ]);
                echo Html::a('&nbsp;禁用','javascript:;',[
                    'data-name'     => 'status',
                    'data-value'    => '0',
                    'data-url'      => Url::to(['status']),
                    'data-form'     => 'ids',
                    'class'         => 'ajax-status-post confirm layui-btn layui-btn-primary layui-icon layui-icon-pause',
                ]);
                /*echo Html::a('&nbsp;删除','javascript:;',[
                    'class'=>'ajax-delete confirm layui-btn layui-btn-primary layui-icon layui-icon-close',
                    'data-url'      => Url::to(['delete']),
                    'data-form'     => 'ids',
                ]);*/

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
        'layout' => '{items} {pager}',

        'columns' => [
            [
                'class' => 'backend\grid\CheckBoxColumn',
                'attribute' => 'id',
                'options'=>['width'=>49,],
            ],
            [
                'options'=>['width'=>80,],
                'attribute'   =>  'id',
            ],
            [
                'options'=>['width'=>200,],
                'attribute'   =>  'keyid',
            ],
            'title',
            [
                'class' => 'backend\grid\SwitchColumn',
                'options'=>['width'=>90,],
                'header' => '是否启用',
                'attribute' => 'status',
            ],
            [
                'class' => 'backend\grid\ActionColumn',
                'options'=>['width'=>100,],
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{update}',//字段管理  {delete}
                'buttonOptions'=>[
                    'update'=>[
                        'class'=>'btn btn-primary btn-xs ajax-iframe-popup',
                        'data-iframe'   => "{width: '450px', height: '280px', title: '更新配置'}",


                    ]
                ],
            ],
        ],
    ]); ?>
</div>
</div>

