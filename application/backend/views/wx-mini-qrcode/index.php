<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \backend\widgets\DisplayStyle;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '小程序码';
$this->params['breadcrumbs'][] = ['label' => '小程序管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("
$('.pic_show').click(function(){
    var width   = $(this).attr('data-width');
    if(width>650){
        width = 650;
    }
    var pic_url = $(this).attr('src');
    layer.open({
      type: 1,
      title: false,
      closeBtn: 0,
      area: [width+'px',width+'px'],
      skin: 'layui-layer-nobg', //没有背景色
      shadeClose: true,
      content: '<img style=\"width: '+width+'px;height: '+width+'px;\" class=\"pic_show\" src=\"'+pic_url+'\" alt=\"\" >'
    });
})
 
   
");
?>
<div class="layuimini-container">
    <div class="layuimini-main">


    <?=$this->render('_tab_menu');?>
    <?=$this->render('_search',['model'=>$searchModel]);?>

    <?= GridView::widget([
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
                'options'=>['width'=>150,],
                'contentOptions'=>['style'=>'text-align: center;'],
                'attribute' => 'pic_url',
                'format' => 'raw',
                'value'  => function($model){
                    return Html::img($model->pic_url,['class'=>'pic_show','data-width'=>$model->width,'style'=>'width:150px;cursor: pointer;']);
                }

            ],
            [
                'options'=>['width'=>150,],
                'attribute' => 'scene',
                'format' => 'raw',

            ],
            [
                //'options'=>['width'=>200,],
                'attribute' => 'page',
                'format' => 'raw',

            ],
            [
                'options'=>['width'=>100,],
                'attribute' => 'width',
                'format' => 'raw',

            ],
            [
                'options'=>['width'=>90,],
                'attribute' => 'is_hyaline',
                'format' => 'raw',
                'value'=>function ($model){
                    if($model->is_hyaline == 'true'){
                        return '是';
                    }else{
                        return '否';
                    }
                }

            ],
            [
                'options'=>['width'=>180,],
                'attribute' => 'created_at',
                'format'    => 'datetime',
            ],
        ],
    ]);
    ?>

  </div>
</div>
