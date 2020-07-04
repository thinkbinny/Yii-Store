<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '系统日志';
$this->params['breadcrumbs'][] = ['label' => '日志管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$js = <<<JS
  $('.ajax-iframe-view').click(function() {
    var Url = $(this).attr('href');
    layer.open({
       title:false, 
       closeBtn: false,
       shadeClose:true,
       btn: ['关闭'],
        area:  ['70%', '80%'],
        shade: 0.8, 
        btnAlign: 'c',
        moveType: 1,
        type: 2, 
        content: [Url], //这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']
    });
    return false;
  })
JS;

$this->registerJs($js);
?>
<div class="layuimini-container">
    <div class="layuimini-main">
    <?=$this->render('_tab_menu');?>
    <?=$this->render('_search',['model'=>$searchModel]);?>

    <?


echo GridView::widget([
        'options'       =>['class'=>'layui-form'],
        'tableOptions'  =>['class'=>'layui-table'],
        'dataProvider'  => $dataProvider,
        'filterModel'   => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout'        => ' {items} {summary} {pager}',
    'pager'         =>[
        //'options'=>['class'=>'layui-table-page'],
        'firstPageLabel'=>"第一页",
        'prevPageLabel'=>'上一页',//'Prev',
        'nextPageLabel'=>'下一页',//'Next',
        'lastPageLabel'=>'最后一页',
    ],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'backend\grid\CheckBoxColumn',
                'attribute' => 'id',
                'options'=>['width'=>49,],
            ],
            [
                'options'=>['width'=>50,],
                'attribute'   =>  'uid',

            ],
            [
                'options'=>['width'=>100,],
                'attribute'   =>  'username',
            ],
            [
                'options'=>['width'=>100,],
                'attribute'   =>  'realname',
            ],
            [
                'options'=>['width'=>120,],
                'attribute'   =>  'action_name',
            ],
            [
                //'options'=>['width'=>100,],
                'attribute'   =>  'action_url',
            ],
            [
                'options'=>['width'=>100,],
                'attribute'   =>  'action_remark',
            ],
            [
                'options'=>['width'=>150,],
                'attribute'   =>  'action_ip',
                'value'       =>  function($data){
                    return long2ip($data->action_ip);
                }
            ],



           [
                'options'   =>['width'=>170,],
                'attribute' => 'created_at',
               'format'     => 'datetime',
            ],
            [
                'options'=>['width'=>60,],
                'class' => 'backend\grid\ActionColumn',
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{view}',// {delete}
                'buttons' => [

                ],
                'buttonOptions'=>[
                    'view'=>[
                        'class'=>'btn btn-info btn-xs ajax-iframe-view',
                    ]
                ],
            ],

        ],
    ]); ?>


</div>
