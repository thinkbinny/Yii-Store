<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Menu;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '抢购管理';
$this->params['breadcrumbs'][] = ['label' => '营销管理', 'url' => 'javascript:;'];
$this->params['breadcrumbs'][] = $this->title;
$css = <<<CSS

.goods-detail{padding:9px 5px !important;}
.goods-detail > div{float: left;}
.goods-detail .goods-image{margin-right: 6px;}
.goods-detail .goods-image .pic_url {width: 72px;height: 72px;background: no-repeat center center / 100%; }
.goods-detail .goods-info{width: 180px;height: 72px;}
.goods-detail .goods-info p { display: block;white-space: normal;margin: 0 0 3px 0;padding: 0 5px;text-align: left; }
.goods-detail .goods-info p.goods-title {max-height: 40px;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical; -webkit-line-clamp: 2; text-align: left !important;white-space: normal; }
.goods-detail .goods-info .goods-attr {border: none;font-size: 12px;color: #7b7b7b }

CSS;
$this->registerCss($css);
?>

<div class="layuimini-container">
    <div class="layuimini-main">
    <?=$this->render('_tab_menu');?>
    <?=$this->render('_search',['model'=>$searchModel]);?>
    <?
    //try {
    echo GridView::widget([
        'options'=>['class'=>'layui-form'],
        'tableOptions'=>['class'=>'layui-table'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_FOOTER,
        'layout' => '{items}',
        'columns' => [
            [
                "contentOptions" => ['style' => 'text-align:center;'],
                'class' => 'backend\grid\CheckBoxColumn',
                'attribute' => 'id',
                'options'=>['width'=>49,],

            ],

            [
                "headerOptions" => ['style' => 'text-align:center;'],
                "contentOptions" => ['style' => 'text-align:center;'],
                'options'=>['width'=>70,],
                'attribute' => 'id',
                'format' => 'raw',

            ],
            [
                'options'   =>['width'=>280,],
                'attribute' => 'title',
                'format'    => 'raw',
                'label'     => '商品信息',
                'contentOptions'   => ['class'=>'goods-detail'],
                'value'     => function($model){
                        //print_r($model->goods);
                    $pic_url = $model -> getImageUrl($model->goods->image_id);
                    $img  = Html::tag('div','',['class'=>'pic_url','style'=>"background-image:url('{$pic_url}')"]);
                    $info = Html::tag('p',$model->title,['class'=>'goods-title']);
                    $html = Html::tag('div',$img,['class'=>'goods-image']);
                    $html .= Html::tag('div',$info,['class'=>'goods-info']);
                    return $html;
                }
            ],
            //'pid',
            //'title',

            [
                "headerOptions" => ['style' => 'text-align:center;'],
                "contentOptions" => ['style' => 'text-align:center;'],
                'attribute' => 'price',
                'format' => 'raw',
                'options'=>['width'=>80,],
                'label'=>'抢购价',
                'value'=>function($model){
                    return '￥'.$model->price;
                }
            ],
            [
                "headerOptions" => ['style' => 'text-align:center;'],
                "contentOptions" => ['style' => 'text-align:center;'],
                'attribute' => 'sales',
                'format' => 'raw',
                'options'=>['width'=>70,],
                'label'=>'销量'
            ],
            [
                "headerOptions" => ['style' => 'text-align:center;'],
                "contentOptions" => ['style' => 'text-align:center;'],
                'attribute' => 'stock',
                'format' => 'raw',
                'options'=>['width'=>70,],
                'label'=>'库存'
            ],
            [
                "headerOptions" => ['style' => 'text-align:center;'],
                "contentOptions" => ['style' => 'text-align:center;'],
                'attribute' => 'sort',
                'format' => 'raw',
                'label'=>'排序',
                'options'=>['width'=>60,],
            ],

            [
                'options'   =>['width'=>190,],
                'attribute' => 'start_time',
                'format'    => 'raw',
                'label'     => '活动时间',
                'value'     => function($model){
                    $html = Html::tag('p','开始时间：'.date('Y-m-d H:i',$model->start_time));
                    $html .= Html::tag('p','结束时间：'.date('Y-m-d H:i',$model->end_time));
                    return $html;
                }
            ],
            [
                'options'=>['width'=>90,],
                'attribute' => 'status',
                'format' => 'raw',
                "headerOptions" => ['style' => 'text-align:center;'],
                "contentOptions" => ['style' => 'text-align:center;'],
                'value' => function($model){
                    return $model->getStatusText();
                },

            ],


            [
                'class' => 'backend\grid\ActionColumn',
                'options'=>['width'=>120,],
                'header' => Yii::t('backend', 'Operate'),
                'template' => '{update} {delete}',/*{copy}   {move} {merge} */
                'buttons' => [
                    'update' =>function($url, $model, $key){

                        if($model->status  == 1 ){
                            $options = [
                                'title' => Yii::t('yii', 'Update'),
                                'class' => 'btn btn-primary btn-xs ajax-iframe-popup',
                                'data-iframe'   => "{width: '1100px', height: '98%', title: '更新活动(禁止修改)',scrollbar:'Yse',btn:false,shadeClose:true}",
                            ];
                            return Html::a('<span class="fa fa-edit"></span> '.Yii::t('yii', 'Update'), $url, $options);
                        }else{
                            $options = [
                                'title' => Yii::t('yii', 'Update'),
                                'class' => 'btn btn-primary btn-xs ajax-iframe-popup',
                                'data-iframe'   => "{width: '1100px', height: '98%', title: '更新活动',scrollbar:'Yse'}",
                            ];
                            return Html::a('<span class="fa fa-edit"></span> '.Yii::t('yii', 'Update'), $url, $options);
                        }
                    }

                ],

            ],
        ],
    ]);
    /*}catch(\Exception $e){
        // todo
    }*/
    ?>
    </div>
</div>
