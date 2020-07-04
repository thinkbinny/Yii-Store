<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

$this->title = '内容列表';

$this->params['breadcrumbs'][] = ['label' => '内容管理', 'url' => ['article/index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCss("


.layui-body{left: 380px;}

.sidebar2 {width: 180px;left:200px;position: fixed;top:60px;bottom: 0px; z-index: 102; background-color: #eaedf1;transition: all .2s;font-size: 12px;}
.sidebar2 .sidebar_title{line-height: 40px;height: 40px;padding-left:30px;font-size: 16px;border-bottom:1px solid #ddd}
.sidebar2 .article_nav_list{overflow: auto;height: 100%;}
.sidebar2 .article_nav_list li {line-height: 35px;}
.sidebar2 .article_nav_list li a{display: block;text-overflow: ellipsis;white-space: nowrap;overflow: hidden;padding-left: 30px;font-size: 13px;}
.sidebar2 .article_nav_list li a:hover, .article_nav_list li a.active{background-color: #fff;}


.layuimini-mini .layui-layout-admin .layui-body{left:240px !important}
.layuimini-mini .sidebar2{left:60px !important}
");
$this->registerJs("

layui.use('form', function() {
    var form = layui.form;
    form.render();  
    //监听表格复选框选择
    //全选
        form.on('checkbox(check-all)', function (data) {
            var child = $(data.elem).parents('table').find('tbody input[type=\"checkbox\"]');  
            child.each(function(index, item){  
              item.checked = data.elem.checked;  
            });  
            form.render('checkbox'); 
        });
    });
   
");

?>

<div class="sidebar2" >
    <div class="sidebar_title">栏目列表</div>
    <ul class="article_nav_list">
        <?
        $category           = new \backend\models\Category();
        $GetCategoryList    = $category->GetCategoryList();
        $html = '';
        $category_id        = Yii::$app->request->get('category_id');
        foreach ($GetCategoryList as $val):
            $active = '';
            if($category_id == $val['id']){
                $active = 'active';
            }

            $a      = Html::a($val['title'],['index','category_id'=>$val['id']],['class'=>$active]);
            $html  .= Html::tag('li',$a);
        endforeach;
        echo $html;
        ?>

    </ul>
</div>

    <div class="layuimini-container">
        <div class="layuimini-main">



            <?=$this->render('_search',['model'=>$searchModel]);?>
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
                    /*[
                        'class' => 'yii\grid\CheckboxColumn',
                        'name' => 'id',
                    ],*/
                    // 'name',
                    [
                        'attribute' => 'title',
                        'label' => '标题',
                        'format' => 'raw',
                        'value' => function ($data) {
                            $url = '/weixin/article/view.html?id='.$data->id;
                            return Html::a($data->title,$url,['target'=>'_blank']);
                        }
                    ],

                    [
                        'attribute' => 'view',
                        'label' => '浏览',
                        'format' => 'raw',
                        'options'=>['width'=>80,],
                        'value' => function ($data) {
                            return $data->view;
                        }
                    ],
                    //'created_at:datetime',
                    [
                        'attribute' => 'updated_at',
                        'label' => '最后更新',
                        'format' => 'raw',
                        'options'=>['width'=>175,],
                        'value' => function ($data) {
                            return date('Y-m-d H:i:s',$data->updated_at);
                        }
                    ],
                    [
                        'class' => 'backend\grid\SwitchColumn',
                        'options'=>['width'=>90,],
                        'header' => '是否显示',
                        'attribute' => 'display',
                    ],
                    [
                        'class' => 'backend\grid\ActionColumn',
                        'options'=>['width'=>140,],
                        'header' => Yii::t('backend', 'Operate'),
                        'template' => '{update} {delete}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                $url = \yii\helpers\Url::to(['update','category_id'=>$model->category_id,'id'=>$model->id]);
                                $options = [
                                    'title' => Yii::t('yii', 'Update'),
                                    'class' => 'btn btn-primary btn-xs ajax-iframe-popup',
                                    'data-iframe'   => "{width: '1100px', height: '90%', title: '更新内容',scrollbar:'Yse'}",
                                ];
                                return Html::a('<span class="fa fa-edit"></span> '.Yii::t('yii', 'Update'), $url, $options);
                            },

                        ],

                    ],
                ],
            ]); ?>


        </div>
    </div>


