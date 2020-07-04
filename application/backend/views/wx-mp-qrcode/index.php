<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \backend\widgets\DisplayStyle;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '二维码';
$this->params['breadcrumbs'][] = ['label' => '微信配置', 'url' => ['wx/config']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("
$('.pic_show').click(function(){
    var pic_url = $(this).attr('src');
    layer.open({
      type: 1,
      title: false,
      closeBtn: 0,
      area: '430px',
      skin: 'layui-layer-nobg', //没有背景色
      shadeClose: true,
      content: '<img class=\"pic_show\" src=\"'+pic_url+'\" alt=\"\" >'
    });
})
 
   
");
?>
<div class="layuimini-container">
    <div class="layuimini-main">
<div id="w3-error-0" style="line-height: 22px;margin-bottom:10px;" class="alert-danger alert fade in" >

    温馨提示：<br>
    1.有效期为0（永久二维码），场景值为纯数字时，最大支持100000。<br>
    2.有效期为0（永久二维码），场景值为字符串时，最大长度为64位。<br>
    3.有效期非0（临时二维码），场景值为纯数字时，最大长度为32位。<br>
    4.有效期非0（临时二维码），场景值为字符串时，最大长度为64位。<br>
    5.永久二维码最多可生成10万个，临时二维码无数量限制，但有效期最长30天。k
</div>

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
                    return Html::img($model->pic_url,['class'=>'pic_show','style'=>'width:150px;cursor: pointer;']);
                }

            ],
            [
                'options'=>['width'=>150,],
                'attribute' => 'scene',
                'format' => 'raw',

            ],
            [
                //'options'=>['width'=>200,],
                'attribute' => 'title',
                'format' => 'raw',

            ],
            [
                'options'=>['width'=>150,],
                'attribute' => 'callback',
                'format' => 'raw',

            ],
            [
                'options'=>['width'=>90,],
                'attribute' => 'scans_count',
                'format' => 'raw',

            ],
            [
                'options'=>['width'=>180,],
                'attribute' => 'valid_days',
                'value'       =>  function($data){
                    if(empty($data->valid_days)){

                        return '永久性';
                    }else{
                        $valid_time = $data->valid_time;
                        return date('Y-m-d H:i:s',$valid_time);
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
