<?php
use yii\helpers\Html;
use yii\grid\GridView;


$this->title = '角色管理';
$this->params['breadcrumbs'][] = ['label' => '管理员设置', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;



?>
<div class="layuimini-container">
    <div class="layuimini-main">

        <?=$this->render('_tab_menu');?>
        <div class="page-toolbar" style="display: none;">
            <div class="layui-btn-group">

                <?php
                echo Html::a('&nbsp;添加',['create'],[
                    'class'         => 'layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine ajax-iframe-popup',
                    'data-iframe'   => "{width: '550px', height: '230px', title: '添加角色'}",
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
            //['class' => 'yii\grid\SerialColumn'],
            [
                'options'=>['width'=>150,],
                'attribute' => 'name',
                'label' => '角色名称'
            ],
            [
                'attribute' => 'description',
                'label' => '描述'
            ],
            [
                'options'=>['width'=>190,],
                'attribute' => 'createdAt',
                'label' => '添加时间',
                'format'=>'datetime'
            ],
            [
                'options'=>['width'=>190,],
                'attribute' => 'updatedAt',
                'label' => '更新时间',
                'format'=>'datetime'
            ],
            [
                'class' => 'backend\grid\ActionColumn',
                'options'=>['width'=>200,],
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{update} {auth} {delete}',// {delete}
                'buttonOptions'=>[
                    'update'=>[
                        'class'=>'btn btn-primary btn-xs ajax-iframe-popup',
                        'data-iframe'   => "{width: '550px', height: '230px', title: '更新角色'}"
                    ],
                    'auth'=>[
                        'class'=>'btn btn-success btn-xs ajax-iframe-popup',
                        'data-iframe'   => "{width: '1050px', height: '90%', title: '角色受权',scrollbar:'yes'}"
                    ] ,

                ],
            ],
            /*[
                'class' => 'backend\grid\ActionColumn',
                'options'=>['width'=>200,],
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{update} {auth} {delete}',
            ],*/
        ],
    ]); ?>

</div>
</div>
