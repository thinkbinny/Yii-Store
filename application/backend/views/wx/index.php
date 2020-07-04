<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \backend\widgets\DisplayStyle;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '粉丝列表';
$this->params['breadcrumbs'][] = ['label' => '微信配置', 'url' => ['wx/config']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("

   
");
?>
<style type="text/css">
    .item-title {width: auto;}
    .item-title .item-title-left{width: 50px;display: inline-block;}
    .item-title .item-title-left img{width: 50px;}
    .item-title .item-title-info{display: inline-block;margin-left: 10px;font-size: 14px;text-overflow: ellipsis;
        white-space: nowrap;/*width: 210px;*/}
    .item-title .item-title-info p{line-height: 30px;}
    .item-title .item-title-info .title{overflow: hidden;height: 30px;}
    .item-title .item-title-info .item_code{}
</style>
<div class="layuimini-container">
    <div class="layuimini-main">
    <?=$this->render('_tab_menu');?>
    <?=$this->render('_search',['model'=>$searchModel]);?>

    <?php ActiveForm::begin(); ?>
    <?= GridView::widget([
        'options'=>['class'=>'layui-form'],
        'tableOptions'=>['class'=>'layui-table'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items}',//{summary}
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'backend\grid\CheckBoxColumn',
                'attribute' => 'id',
                'options'=>['width'=>49,],
            ],
            [
                'attribute' => 'uid',
                'format' => 'raw',
                'options'=>['width'=>100,],
            ],
            [
                'attribute' => 'nickname',
                'format'    => 'raw',
                'value'     => function($data){
                    $pic_url = Html::img($data->headimgurl,[]);
                    $info    = "<p class='title'>{$data->nickname}</p>";

                    $html     = "<div class='item-title'><div class='item-title-left'>{$pic_url}</div><div class='item-title-info'>{$info}</div> </div>";
                    //$html   = Html::tag('div',$pic_url);
                    return $html;
                }

            ],
           // 'url:url',

            [
                'options'=>['width'=>90,],
                'attribute' => 'subscribe',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->getSubscribeText($data->subscribe);
                }
            ],
            [
                'options'=>['width'=>90,],
                'attribute' => 'country',
                'format' => 'raw',
            ],
            [
                'options'=>['width'=>120,],
                'attribute' => 'province',
                'format' => 'raw',
            ],
            [
                'options'=>['width'=>120,],
                'attribute' => 'city',
                'format' => 'raw',
            ],

            [
                'options'=>['width'=>170,],
                'attribute' => 'updated_at',
                'value'       =>  function($data){
                    return date('Y-m-d H:i:s',$data->updated_at);
                }
            ],

            /*
            [
                'options'=>['width'=>150,],
                'class' => 'common\grid\ActionColumn',
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{update} {delete}',// {delete}
                'buttons' => [

                ],
            ],
            */
        ],
    ]);
    //echo Html::submitButton('推送消息', ['class' => 'layui-btn']);
    ActiveForm::end(); ?>
</div>
</div>
